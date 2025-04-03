<?php

namespace App\Services\Core;

use App\Enums\SettingKey;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Artisan;
use App\Service\Admin\Core\SettingService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpgradeService extends Controller
{ 
     public SettingService $settingService;
 
     /**
      * __construct
      *
      * @param SettingService $settingService
      */
     public function __construct(SettingService $settingService) { 
 
         $this->settingService = $settingService;
     }

     /**
      * loadIndex
      *
      * @return View
      */
     public function loadIndex(): View {

          $file_path        = base_path('update_info.md');
          $markdownContent  = File::get($file_path);
          $file_contents    = $this->parseMarkdown($markdownContent);
          $current_version  = site_settings("app_version");
          $new_version      = config('requirements.core.appVersion');
          $title            = translate("update")." $new_version";
          $caution_messages = Arr::get($file_contents, "Caution","");

          unset($file_contents['Caution']);

          return view('update.index', compact(
              'current_version',
              'new_version',
              'title',
              'file_contents',
              'caution_messages'
          ));
     }

     /**
      * loadVerify
      *
      * @return View
      */
     public function loadVerify(): View {

          $current_version = site_settings('app_version');
          $new_version     = config('requirements.core.appVersion'); 
          $title           = "update ".$new_version;
          return view('update.verify', compact(
               
               'current_version',
               'new_version',
               'title'
          ));
     }

     /**
      * store
      *
      * @param Request $request
      * 
      * @return RedirectResponse
      */
     public function store(Request $request): RedirectResponse {

          $admin_credentials = $request->validate([
               'username' => ['required'],
               'password' => ['required'],
               
          ]);

          $request->validate([
               'purchased_code' => ['required'],
          ]);

          try {
               
               if (Auth::guard('admin')->attempt($admin_credentials)) {
   
                   $buyer_domain   = url()->current();
                   $purchased_code = $request->purchased_code;
                   $response = Http::withoutVerifying()->get('https://license.igensolutionsltd.com', [
                       'buyer_domain'   => $buyer_domain,
                       'purchased_code' => $purchased_code,
                   ]);
                  
                   if($response->json()['status']) {
                       if(File::exists(base_path('update_info.md'))) {
                           Session::put('is_verified', true);
                           $notify[] = ['success', "Verification Successfull"];
                           return redirect()->route('admin.update.index')->withNotify($notify);
                       } 
                       $notify[] = ['error', "Files are not available"];
                       return back()->withNotify($notify); 
                       
                   } else {
                       $notify[] = ['error', "Invalid licence key"];
                       return back()->withNotify($notify);
                   }
               }
               return back()->withErrors([
                   'email' => 'The provided credentials do not match our records.',
               ]);
           } catch(\Exception $e) {
               
               $developmentMessage = translate("Server Error: ").$e->getMessage();
               $notify[] = ['info', getEnvironmentMessage($developmentMessage)];

               return back()->withNotify($notify);
           }
     }

     /**
      * update
      *
      * @return RedirectResponse
      */
     public function update(): RedirectResponse
     {
          $current_version = site_settings('app_version');
          $new_version = config('requirements.core.appVersion');
          $file_path = base_path('update_info.md');
          $supportURL = SettingKey::SUPPORT_URL->value;
      
          if (version_compare($new_version, $current_version, '>')) {
               try {
                    session(["queue_restart" => true]);
          
                    $migrationFiles = [
                         '/database/migrations/2024_10_13_090746_create_campaign_unsubscribes_table.php' => function () {
                              return !Schema::hasTable('campaign_unsubscribes');
                         },
                         '/database/migrations/2024_10_15_055153_add_verification_status_to_contacts_table.php' => function () {
                              return !Schema::hasColumn('contacts', 'verification_status');
                         },
                         '/database/migrations/2025_02_24_163258_restructure_contact_groups_table.php' => function () {
                              if (!Schema::hasTable('contact_groups')) {
                                   return true; 
                              }
                              $columnInfo = DB::select("SHOW COLUMNS FROM `contact_groups` WHERE Field = 'status'")[0];
                              $type = $columnInfo->Type;
                              return !str_contains($type, "enum('active','inactive')");
                         },
                         '/database/migrations/2025_03_17_152051_update_status_column_in_contacts_table.php' => function () {
                              return !Schema::hasColumn('contacts', 'status'); 
                         },
                    ];
          
                    // Define drop operations (if any) with conditions
                    $dropTableOrColumn = [
                         // Example: '/database/migrations/some_drop_migration.php' => function () {
                         //     return Schema::hasTable('some_table');
                         // },
                    ];
          
                    // Run migrations only if their conditions are met
                    foreach ($migrationFiles as $migrationFile => $condition) {
                         if ($condition()) {
                              Artisan::call('migrate', ['--force' => true, '--path' => $migrationFile]);
                         }
                    }
          
                    // Run drop migrations only if their conditions are met
                    foreach ($dropTableOrColumn as $drop => $condition) {
                         if ($condition()) {
                              Artisan::call('migrate', ['--force' => true, '--path' => $drop]);
                         }
                    }
          
                    // Clean up and finalize
                    if (File::exists($file_path)) {
                         File::delete($file_path);
                    }
                    Artisan::call('queue:restart');
                    Artisan::call('optimize:clear');
                    $this->versionUpdate($new_version);
          
                    $notify[] = ['success', 'Successfully updated database.'];
                    return redirect()->route('admin.dashboard')->withNotify($notify);
               } catch (\Exception $e) {
                    $developmentMessage = translate("Server Error: ") . $e->getMessage();
                    $productionMessage = translate("Please contact support at: ") . $supportURL;
          
                    $notify[] = ['info', getEnvironmentMessage($developmentMessage, $productionMessage)];
                    return back()->withNotify($notify);
               }
          }
      
          $notify[] = ['error', "No update needed"];
          return back()->withNotify($notify);
      }


     ## ----------------------------------- ##

     /**
      * parseMarkdown
      *
      * @param mixed $content
      * 
      * @return array
      */
     private function parseMarkdown($content): array {

          $sections = $this->splitIntoSections($content);
          $parsed_sections = [];
      
          foreach ($sections as $section) {
              $parsed_section = $this->parseSection($section);
              if ($parsed_section) {
                  $parsed_sections[$parsed_section['title']] = $parsed_section['content'];
              }
          }
      
          return $parsed_sections;
     }

     /**
      * splitIntoSections
      *
      * @param mixed $content
      * 
      * @return array
      */
     private function splitIntoSections($content): array {
          $sections = explode('## ', $content);
          array_shift($sections);
          return $sections;
     }
      
     /**
      * parseSection
      *
      * @param mixed $section
      * 
      * @return array
      */
     private function parseSection($section): array|null {
          $split_content = preg_split("/\r\n|\n/", trim($section), 2);
          $title = trim($split_content[0]);
      
          if (count($split_content) == 2) {
              $content = trim($split_content[1]);
          } else {
              $content = '';
          }
      
          return (!empty($title) && !empty($content)) ? compact('title', 'content') : null;
     }


     /**
      * versionUpdate
      *
      * @param mixed $new_version
      * 
      * @return void
      */
     public function versionUpdate($new_version): void {

          $current_version = [
              
              'app_version' => $new_version
          ];
          $this->settingService->updateSettings($current_version);
          
     }
}