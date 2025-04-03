<?php

namespace App\Http\Controllers\Admin\Core;

use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\Session;
use App\Service\Admin\Core\SettingService;
use Illuminate\Support\Facades\App;

class SettingController extends Controller
{
    public SettingService $settingService;
    public function __construct(SettingService $settingService) { 

        $this->settingService = $settingService;
    }

    /**
     * @param $type
     * 
     * @return \Illuminate\View\View
     * 
     * @throws Exception $error
     * 
     */
    public function index($type = "general") {
    
        try {
            Session::put("menu_active", true);
            $data = $this->settingService->getIndex($type);
            return view("admin.setting.$type", $data);
            
        } catch (\Exception $error) {
            
            $notify[] = ["error", translate("System Error: "). $error->getMessage()];
            return redirect()->route("admin.dashboard")->withNotify($notify);
        }
    }

    public function store(Request $request) {
        
        $reload  = false;
        $status  = true;
        $message = translate('Updated settings successfully');
        $extra   = null;
        if($request->has('site_settings')) {

            try {
                $path = base_path('.env');
                $env_content = file_get_contents($path);
                $validations = $this->settingService->validationRules($request->site_settings);
                $request->validate($validations['rules'],$validations['message']);
                
                if(isset($request->site_settings['time_zone'])) {
                    
                    $timeLocationFile = config_path('timesetup.php');
                    $time = "<?php \$timelog = '".$request->site_settings['time_zone']."' ?>";
                    file_put_contents($timeLocationFile, $time);
                   
                }
                if (isset($request->site_settings['debug_mode'])) {

                    $debugMode = $request->site_settings['debug_mode'] == StatusEnum::TRUE->status();
                    $envFilePath = $path; 
                    $envContent = file_exists($envFilePath) ? file_get_contents($envFilePath) : '';
                    $newEnv = $debugMode ? 'local' : 'production';
                    $newDebug = $debugMode ? 'true' : 'false';
                    $updatedEnvContent = str_replace(
                        ['APP_DEBUG=true', 'APP_DEBUG=false'],
                        ["APP_DEBUG={$newDebug}", "APP_DEBUG={$newDebug}"],
                        $envContent
                    );
                    file_put_contents($envFilePath, $updatedEnvContent);
                    $reload = true;
                }

                if (isset($request->site_settings['theme_dir']) && $request->site_settings['theme_dir'] != site_settings('theme_dir')) { 

                    $current_language = Language::where('code', App::getLocale())->first();
                    if($current_language && $current_language->ltr == StatusEnum::FALSE->status()) {

                        $siteSettings = $request->input('site_settings');
                        $siteSettings['theme_dir'] = StatusEnum::FALSE->status();
                        $request->merge(['site_settings' => $siteSettings]);
                        $extra = translate("Current language doesnt have ltr/rtl compatibility");   
                    } 
                    $reload = true;
                }
                if (isset($request->site_settings['theme_mode']) && $request->site_settings['theme_mode'] != site_settings('theme_mode')) { 
                    
                    $reload = true;
                }
                $this->settingService->updateSettings($request->site_settings);
                $message = translate('Settings Updated Successfully');

            } catch (\Exception $exception) {
                
                $status = false;
                $message = $exception->getMessage();
            }
            
        } else {

            $status  = false;
            $message = translate("Nothing to update");
        }
        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message.', '.$extra
        ]);
    }
}
