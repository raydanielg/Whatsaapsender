<?php

namespace App\Services\System\Contact;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Import;
use App\Models\Contact;
use App\Jobs\ImportJob;
use App\Enums\StatusEnum;
use App\Enums\SettingKey;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Enums\Common\Status;
use App\Enums\ContactAttributeEnum;
use App\Models\ContactGroup;
use App\Service\MailService;
use Illuminate\Http\Response;
use App\Imports\ContactImport;
use App\Traits\CollectionTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Service\Admin\Core\FileService;
use App\Exceptions\ApplicationException;
use Illuminate\Database\Eloquent\Builder;
use App\Service\Admin\Core\SettingService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContactService
{ 
    use CollectionTrait; 

    protected SettingService $settingService;
    public FileService $fileService;

    public function __construct()
    {
        $this->settingService = new SettingService;
        $this->fileService    = new FileService;
    }

    ## Contact attribute setting related functions:

    /**
     * getContactAttributes
     *
     * @param User|null $user
     * 
     * @return View
     */
    public function getContactAttributes(?User $user = null): View {

        $title = translate("Manage Contact Attributes");
        $contactAttributes  = $user 
                                ? $user->contact_meta_data 
                                : site_settings(SettingKey::CONTACT_META_DATA->value, []);
        $collection         = $this->collect(json_decode($contactAttributes, true));
        $contactAttributes  = $this->paginate(
            $this->filterByKey(
                $this->searchCollection($collection),
                last(explode('.', request()->route()->getName()))
            ),
            paginateNumber(site_settings(SettingKey::PAGINATE_NUMBER->value))
        );
        
        return view($user ? 'user.contact.settings.index' : 'admin.contact.settings.index', compact('title', 'contactAttributes'));
    }

    /**
     * saveContactAttributes
     *
     * @param array $data
     * @param User|null $user
     * 
     * @return array
     */
    public function saveContactAttributes(array $data, ?User $user = null): array {
        
        $new_attribute_name = strtolower(str_replace(' ', '_', $data["attribute_name"]));
        
        $new_data[SettingKey::CONTACT_META_DATA->value] = [
            $new_attribute_name => [
                "type"   => (int) Arr::get($data, "attribute_type"),
                "status" => Status::ACTIVE->value,
            ]
        ];
        
        $old_data = $user 
            ? json_decode($user->contact_meta_data, true) 
            : json_decode(site_settings(SettingKey::CONTACT_META_DATA->value, []), true);
    
        if (isset($data["old_attribute_name"])) {
            $old_attribute_name = strtolower(str_replace(' ', '_', $data["old_attribute_name"]));
            
            if (isset($old_data[$old_attribute_name])) {
                $old_data[$new_attribute_name] = array_merge($old_data[$old_attribute_name], $new_data['contact_meta_data'][$new_attribute_name]);
                
                if ($old_attribute_name !== $new_attribute_name) {
                    unset($old_data[$old_attribute_name]);
                }
            }
        } else {
            
            $old_data[$new_attribute_name] = $new_data['contact_meta_data'][$new_attribute_name];
        }
    
        $final_data['contact_meta_data'] = $old_data;
        return $final_data;
    }   

    /**
     * deleteContactAttributes
     *
     * @param array $data
     * @param User|null $user
     * 
     * @return array
     */
    public function deleteContactAttributes(array $data, ?User $user = null): array {
        
        $attribute_name = strtolower(str_replace(' ', '_', $data["attribute_name"]));
        $old_data       = $user ? json_decode($user->contact_meta_data, true) : json_decode(site_settings('contact_meta_data'), true);
        unset($old_data[$attribute_name]);
        $final_data['contact_meta_data'] = $old_data;
        return $final_data;
    }

    /**
     * contactAttributeStatusUpdate
     *
     * @param Request $request
     * @param User|null $user
     * 
     * @return JsonResponse
     */
    public function contactAttributeStatusUpdate(Request $request, ?User $user = null): JsonResponse|ApplicationException {

        $contactAttributes  = [];

        if(!$user) $contactAttributes   = site_settings(SettingKey::CONTACT_META_DATA->value, []);
        if($user) $contactAttributes    = $user->contact_meta_data;
        
        $contactAttributes  = json_decode($contactAttributes, true);
        $attributeName      = $request->input('name');
        $attribute          = Arr::get($contactAttributes, $attributeName);
        
        if (is_null($attribute)) throw new ApplicationException("Attribute not found", Response::HTTP_NOT_FOUND);
        
        $updatedStatus = Arr::get($attribute, "status") == StatusEnum::TRUE->status() 
                            ||  Arr::get($attribute, "status") == Status::ACTIVE->value
                                ? Status::INACTIVE->value
                                : Status::ACTIVE->value;
        Arr::set($attribute, "status", $updatedStatus);
        Arr::set($contactAttributes, $attributeName, $attribute);
        
        if($user) {

            $user->contact_meta_data = $contactAttributes;
            $user->save();
        } else {

            $this->settingService->updateSettings([
                SettingKey::CONTACT_META_DATA->value => $contactAttributes
            ]);
        }

        return response()->json([
            'reload'    => true,
            'status'    => true,
            'message'   => translate('Contact Attribute status updated successfully'),
        ]);
    }


    ## Contact group related functions

    /**
     * getContactGroups
     *
     * @param int|null $id
     * @param User|null $user
     * 
     * @return View
     */
    public function getContactGroups(?int $id = null, ?User $user = null): View {
        
        $title          = translate("Manage Contact Groups");
        $contactGroups  = ContactGroup::date()
                                        ->search(['name'])
                                        ->filter(['status'])
                                        ->latest()
                                        ->when($id, fn(Builder $q): Builder =>
                                            $q->where("id", $id))
                                        ->with(['contacts'])
                                        ->when($user, fn(Builder $q) =>
                                            $q->where("user_id", $user->id), 
                                                fn(Builder $q): Builder =>
                                                    $q->admin())
                                        ->paginate(paginateNumber(site_settings(SettingKey::PAGINATE_NUMBER->value, 10)))
                                        ->onEachSide(1)
                                        ->appends(request()->all());
        
        return view($user 
                            ? "user.contact.groups.index" 
                            : 'admin.contact.groups.index', 
            compact('title', 'contactGroups'));
    }

    /**
     * saveContactGroups
     *
     * @param array $data
     * @param string|null $uid
     * @param User|null $user
     * 
     * @return RedirectResponse
     */
    public function saveContactGroups(array $data, ?string $uid = null, ?User $user = null): RedirectResponse {

        $contactGroup = null;

        if($uid) {

            $contactGroup = ContactGroup::when($user, 
                                            fn(Builder $q): Builder =>
                                                $q->where("user_id", $user->id), 
                                                    fn(Builder $q): Builder =>
                                                        $q->admin())
                                            ->when($uid, fn(Builder $q): Builder =>
                                                $q->where("uid", $uid))
                                            ->first();
            if(!$contactGroup) throw new ApplicationException("Contact group not found", Response::HTTP_NOT_FOUND);
        }
        
        $contactGroup = $contactGroup 
                            ? $contactGroup
                            : new ContactGroup();
        $contactGroup->user_id  = @$user ? $user->id : null;
        $contactGroup->name     = Arr::get($data, "name");
        $contactGroup->save();
        $notify[] = ['success', translate("Contact group updated successfully")];
        return back()->withNotify($notify);
    }

    /**
     * deleteContactGroup
     *
     * @param array $data
     * @param string|null $uid
     * @param User|null $user
     * 
     * @return RedirectResponse
     */
    public function deleteContactGroup(array $data, ?string $uid = null, ?User $user = null): RedirectResponse {

        $contactGroup = ContactGroup::when($user, 
                                        fn(Builder $q): Builder =>
                                            $q->where("user_id", $user->id), 
                                                fn(Builder $q): Builder =>
                                                    $q->admin())
                                        ->where("uid", $uid)
                                        ->with(['contacts'])
                                        ->first();

        if(!$contactGroup) throw new ApplicationException("Contact group not found", Response::HTTP_NOT_FOUND);

        $contactGroup?->contacts()?->delete();
        $contactGroup->delete();

        $notify[] = ['success', translate("Contact group deleted successfully")];
        return back()->withNotify($notify);
    }

    ## Contact Related Functions

    /**
     * getContacts
     *
     * @param int|string|null $groupId
     * @param User|null $user
     * 
     * @return View
     */
    public function getContacts(int|string|null $groupId, ?User $user = null): View {

        $title              = translate("Contact List");
        $contactMetaData    = $user 
                                ? $user->contact_meta_data
                                : site_settings(SettingKey::CONTACT_META_DATA->value, []);
        $contactMetaData    = json_decode($contactMetaData, true);
        $filtered_meta_data = $this->filterMetaData($contactMetaData, Status::ACTIVE->value);
        $contacts           = $this->fetchContacts(groupId: $groupId, user:$user); 
        $groups             = $this->pluckContactGroup("name", "id", $user);
        $csv_data           = $this->getCsvExportData($groupId, $user);
        
        return view($user ? "user.contact.index" :'admin.contact.index', 
        compact('title', 'contacts', 'filtered_meta_data', 'groups', 'groupId', 'csv_data'));
    }

    /**
     * createContact
     *
     * @param int|string|null|null $groupId
     * @param User|null $user
     * 
     * @return View
     */
    public function createContact(int|string|null $groupId = null, ?User $user = null): View {
        
        $title              = translate("Add Contacts");
        $contactMetaData    = $user 
                                ? $user->contact_meta_data
                                : site_settings(SettingKey::CONTACT_META_DATA->value, []);

        $contactMetaData    = json_decode($contactMetaData, true);
        $filtered_meta_data = $this->filterMetaData($contactMetaData, Status::ACTIVE->value);
        
        $groups = $this->pluckContactGroup("name", "id", $user);

        return view($user ? "user.contact.create" : 'admin.contact.create', 
            compact(
                'title', 
                'filtered_meta_data', 
                'groups', 
                'groupId'));
    }

    /**
     * exportContacts
     *
     * @param Request $request
     * @param int|string|null $groupId
     * 
     * @return BinaryFileResponse
     */
    public function exportContacts(Request $request, int|string|null $groupId): BinaryFileResponse { 

        $file_name      = 'contacts_export_' . Carbon::now()->format('Y_m_d_His') . '.csv';
        $data_config    = json_decode($request->input('data_config'), true);
        $contacts       = $this->fetchContacts(true, $groupId);
        $data           = $this->fileService->prepareExportData($contacts, $data_config);
        $csv_file_path  = $this->fileService->generateCsvFile($data, "contact_exports", $file_name);

        $headers = [
            'X-Status'   => 'true',
            'X-Message'  => translate("Successfully generated contact CSV file"),
            'X-Filename' => $file_name 
        ];
        return response()->download($csv_file_path, $file_name, $headers);
    }

    /**
     * contactSave
     *
     * @param array $data
     * @param User|null $user
     * 
     * @return RedirectResponse
     */
    public function contactSave(array $data, ?User $user = null): RedirectResponse {
        
        $contactGroup = ContactGroup::when($user, fn(Builder $q): Builder =>
                                            $q->where("user_id", $user->id), fn(Builder $q): Builder =>
                                                $q->admin())
                                        ->where("id", Arr::get($data, "group_id"))
                                        ->first();
        if(!$contactGroup) throw new ApplicationException("Group is inactive or invalid", Response::HTTP_NOT_FOUND);

        $isBulk = Arr::get($data,"single_contact") == "false";

        if($isBulk) return $this->saveBulkContact($data, $user);
                
        return $this->saveSingleContact($data, $user);
    }

    /**
     * saveSingleContact
     *
     * @param array $data
     * @param User|null $user
     * 
     * @return RedirectResponse
     */
    public function saveSingleContact(array $data, ?User $user): RedirectResponse {
        
        $mailService    = new MailService();
        $email          = Arr::get($data, "email_contact");  
        if($email && site_settings(SettingKey::EMAIL_CONTACT_VERIFICATION->value) == StatusEnum::TRUE->status()) {
            
            $result = $mailService->verifyEmail($email);
            $isValid = Arr::get($result, "valid");
            $data['email_verification'] = $isValid ? "verified" : "unverified";
        }
        $data["user_id"]   = @$user ? $user->id : null;
        $data["meta_data"] = $this->contactMetaData($data);
        unset($data["single_contact"]);
        
        $this->updateOrInsert($data);
        $this->updateGroupMetaData($data);

        $notify[] = ["success", translate('Single contact saved successfully')];
        return back()->withNotify($notify);
    }

    /**
     * updateOrInsert
     *
     * @param mixed $data
     * 
     * @return void
     */
    public function updateOrInsert($data): void {

        $uid = Arr::get($data, "uid");
        Contact::updateOrCreate(["uid" => $uid], $data);
    }

    /**
     * updateGroupMetaData
     *
     * @param mixed $data
     * 
     * @return void
     */
    public function updateGroupMetaData($data): void
    {
        if (!Arr::exists($data, "meta_data")) return; 
        
        $meta_data = collect($data["meta_data"])
                        ->map(function ($attribute_values) {
                            return collect($attribute_values)
                                ->mapWithKeys(function ($attribute_value, $attribute_key) {
                                    if ($attribute_key === "value") {
                                        return ["status" => true];
                                    }
                                    return [$attribute_key => $attribute_value];
                                })
                                ->except(["value"])
                                ->toArray();
                        })
                        ->toArray();

        $group              = ContactGroup::find($data["group_id"]);
        $currentAttributes  = json_decode($group->meta_data, true);
        $mergedAttributes   = $currentAttributes ? array_merge($currentAttributes, $meta_data) : $meta_data;
        $group->meta_data   = json_encode($mergedAttributes);
        $group->save();
    }

    /**
     * contactMetaData
     *
     * @param array $data
     * 
     * @return array
     */
    public function contactMetaData(array $data): array|null {
         
        if (!Arr::exists($data, "meta_data")) return null; 
    
        $refinedAttribute = collect($data["meta_data"])
                                ->mapWithKeys(function ($value, $key) {

                                    if(!$value) return [];

                                    $keyParts = explode("::", $key);
                                    return [
                                        $keyParts[0] => [
                                            "value" => $value,
                                            "type"  => $keyParts[1]
                                        ]
                                    ];
                                })->toArray();
    
        return $refinedAttribute;
    }

    /**
     * saveBulkContact
     *
     * @param array $data
     * @param User|null $user
     * 
     * @return RedirectResponse
     */
    public function saveBulkContact(array $data, ?User $user = null): RedirectResponse
    {
        $locationKeys   = explode(",", Arr::get($data, "location.0", ""));
        $values         = explode(",", Arr::get($data, "value.0", ""));
        
        $mappedDataInput = collect($locationKeys)
                                ->mapWithKeys(function ($key, $index) use ($values) {
                                    return [$key => Arr::get($values, $index, "")];
                                })
                                ->chunk(100)
                                ->flatMap(function (SupportCollection $chunk) {
                                    
                                    return $chunk;
                                })
                                ->toArray();
    
        $data["mappedDataInput"] = $mappedDataInput;
        unset($data["single_contact"], $data["file"], $data["import_contact"], $data["location"], $data["value"]);
        
        $filePath   = filePath()["contact"]["path"];
        $fileName   = Arr::get($data, "file__name", "");
        $mime       = explode(".", $fileName)[1] ?? "default";
        $data       = $this->prepParams($filePath, $mime, $user, null, $data);
        $imported   = Import::create($data);
        
        ImportJob::dispatch($imported->id);
    
        $notify[] = ["success", translate("Your contact upload request is being processed")];
        return back()->withNotify($notify);
    }
    
    /**
     * prepParams
     *
     * @param string $path
     * @param string $mime
     * @param User|null $user
     * @param string|null $type
     * @param array $contact_structure
     * 
     * @return array
     */
    public function prepParams(string $path, string $mime, ?User $user, ?string $type, array $contact_structure): array{

        $data = $contact_structure;
        unset($contact_structure["file__name"], $contact_structure["group_id"]);
        return [
            'user_id'           => @$user?->id ? $user->id : null,
            'name'              => $data["file__name"],
            'path'              => $path,
            'mime'              => $mime,
            'group_id'          => $data["group_id"],
            'type'              => $type,
            'contact_structure' => $contact_structure,
        ];
    }

    /**
     * deleteContact
     *
     * @param string $uid
     * @param User|null $user
     * 
     * @return RedirectResponse
     */
    public function deleteContact(string $uid, ?User $user = null): RedirectResponse {

        $contact = Contact::when($user, fn(Builder $q): Builder =>
                                    $q->where("user_id", $user->id), 
                                        fn(Builder $q):Builder =>
                                            $q->admin())
                                ->where("uid", $uid)
                                ->first();
        if(!$contact) throw new ApplicationException("Contact not found", Response::HTTP_NOT_FOUND);
        $contact->delete();

        $notify[] = ['success', translate("Contact deleted successfully")];
        return back()->withNotify($notify);
    }

    /**
     * fetchContacts
     *
     * @param bool $export
     * @param int|string|null|null $groupId
     * @param User|null $user
     * 
     * @return Collection
     */
    private function fetchContacts(bool $export = false, int|string|null $groupId = null, ?User $user = null): Collection|LengthAwarePaginator {

        return Contact::when($groupId, fn(Builder $q): Builder => 
                    $q->where('group_id', $groupId))
                ->when($user, fn(Builder $q): Builder => 
                    $q->where('user_id', $user->id), 
                        fn(Builder $q) : Builder =>
                            $q->admin())
                ->search([
                    'first_name|last_name',
                    'first_name',
                    'last_name',
                    'whatsapp_contact',
                    'email_contact',
                    'sms_contact'
                ])
                ->filter(['status', 'email_verification'])
                ->latest()
                ->date()
                ->when(
                    $export,
                    fn(Builder $q): Collection => $q->get(),
                    fn(Builder $q): LengthAwarePaginator => 
                        $q->paginate(paginateNumber(site_settings('paginate_number')))
                            ->onEachSide(1)
                            ->appends(request()->all())
                );
    }

    /**
     * filterMetaData
     *
     * @param array|null $contact_meta_data
     * @param string $status
     * 
     * @return array
     */
    private function filterMetaData(array|null $contact_meta_data, string $status): array {
        
        return collect($contact_meta_data)
                ->filter(function ($meta_data) use($status) {
                        $meta_data = (object) $meta_data;
                        
                        return @$meta_data?->status == $status;
                    })->toArray();
    }

    /**
     * getCsvExportData
     *
     * @param int|string|null $groupId
     * 
     * @return array
     */
    public function getCsvExportData(int|string|null $groupId = null, ?User $user = null): array {

        $route = $user 
                    ? route('user.contact.export', ['group_id' => $groupId]) 
                    : route('admin.contact.export', ['group_id' => $groupId]); 
        return [
            "url"    => $route,
            "method" => "POST",
            "parameters" => [
                "first_name" => [
                    "type" => "string"
                ],
                "last_name" => [
                    "type" => "string"
                ],
                "whatsapp_contact" => [
                    "type" => "string"
                ],
                "email_contact" => [
                    "type" => "string"
                ],
                "sms_contact" => [
                    "type" => "string"
                ],
                "meta_data" => [
                    "type" => "object",
                    "format" => [
                        "date_of_birth" => [
                            "data" => "value"
                        ]
                    ]
                ],
                "created_at" => [
                    "type" => "datetime"
                ]
            ]
        ];
    }

    /**
     * pluckContactGroup
     *
     * @param string $key
     * @param string $value
     * @param User|null $user
     * 
     * @return array
     */
    public function pluckContactGroup(string $keyColumn, string $valueColumn, ?User $user = null): array {

        return  ContactGroup::when($user, 
                            fn(Builder $q): Builder =>
                                $q->where("user_id", $user->id),
                                    fn(Builder $q): Builder => 
                                        $q->whereNull("user_id"))
                                
                                ->pluck($valueColumn, $keyColumn)
                                ->toArray();
    }

    ## Update SIngle COntact Verfication

    /**
     * singleContactEmailVerification
     *
     * @param Request $request
     * @param User|null $user
     * 
     * @return JsonResponse
     */
    public function singleContactEmailVerification(Request $request, ?User $user = null): JsonResponse {

        $contact = Contact::when($user, 
                        fn(Builder $q): Builder => 
                            $q->where("user_id", $user->id), 
                                fn(Builder $q): Builder => 
                                    $q->admin())
                            ->where("uid", $request->input('uid'))
                            ->first();
        if (!$contact) throw new ApplicationException("Invalid Contact", Response::HTTP_NOT_FOUND);
        $contact->email_verification = $request->input('email_verification') == 'true' ? 'verified' : 'unverified';
        $contact->update();

        return response()->json([
            'status'  => true,
            'message' => translate("Contact Email Verification Status has been updated"),
            'reload'  => true
        ]);
    }

    /**
     * contactUploadFile
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function contactUploadFile(Request $request): JsonResponse {

        list($fileName, $filePath) = $this->fileService->uploadContactFile($request->file('file'));
        return response()->json([

            "status" => true, 
            "file_name" => $fileName, 
            "file_path" => $filePath
        ]);
    }



    

    

  

    

   

    ## Todo: 
    
    public function getCsvRows($file) {

        return Excel::toArray(new ContactImport, $file);
    }

    public function getNewRowData($row_data) {

        $new_row_data = [];
        $keys = array_map(function ($key) {
            return strtolower(str_replace(' ', '_', $key)); 
        }, $row_data[0][0]);
        
        foreach ($row_data[0] as $index => $v) {
            
            $new_row_data[$index] = array_combine($keys, $v);
        }
        return $new_row_data;
    }

    public function saveEachContact($new_row_data, $updated_column, $group_id, $data) {

        $mailService    = new MailService();
        foreach (array_chunk($new_row_data, 200) as $chunks) {

            foreach ($chunks as $values) {

                $current_data = $data;
                $meta_data = [];
                foreach ($values as $column_key => $column_value) {
                    
                    if (array_key_exists($column_key, $current_data)) {

                        if($column_key == SettingKey::EMAIL_CONTACT->value
                            && site_settings(SettingKey::EMAIL_CONTACT_VERIFICATION->value) == StatusEnum::TRUE->status()) {

                            $result = $mailService->verifyEmail($column_value);
                            $isValid = Arr::get($result, "valid");
                            $data['email_verification'] = $isValid ? "verified" : "unverified";
                            $current_data[$column_key] = strtolower($column_value); 

                        } else {

                            $current_data[$column_key] = strtolower($column_value);
                        }
                    } else {

                        $column_index = array_search($column_key, array_keys($values));
                        if (isset($updated_column[$column_index][$column_key])) {
                            $meta_data[$column_key] = [
                                "value" => strtolower($column_value),
                                "type"  => $updated_column[$column_index][$column_key]["type"]
                            ];
                        }
                    }
                }
                $current_data["meta_data"] = $meta_data;
                $current_data["group_id"]  = $group_id;
                Contact::create($current_data);
            }
        }
    }
    
    public function importWithNewRow($data, $row_data, $group_id) {

        unset($data["new_row"], $data["file__name"]);
        $updated_column = $this->transformColumns($data["mappedDataInput"]);
        $row_data       = $this->transformRowData($row_data, $updated_column);
        unset($data["mappedDataInput"]);
        $this->saveEachContact($this->getNewRowData($row_data), $updated_column, $group_id, $data);
    }

    public function importWithoutHeader($data, $row_data, $group_id) {

        unset($data["new_row"], $data["file__name"]);
        $updated_column = $this->transformColumns($data["mappedDataInput"]);
        $row_data = $this->transformRowData($row_data, $updated_column);
        unset($data["mappedDataInput"]);
        $new_row_data = $this->getNewRowData($row_data);
        array_splice($new_row_data, 0, 1);
        $this->saveEachContact($new_row_data, $updated_column, $group_id, $data);
    }

    public function importContactFormFile($name, $filePath, $data, $group_id, $user_id = null) {
        
        $row_data = $this->getCsvRows(storage_path("../../$filePath/$name")); 
        
        $data["user_id"] = $user_id;

        if($data["new_row"] == "true") {
            
            $this->importWithNewRow($data, $row_data, $group_id);

        } else {
            
            $this->importWithoutHeader($data, $row_data, $group_id);
        }
        unlink(storage_path("../../$filePath/$name"));
    }

    public function transformRowData($row_data, $updated_column) {
        
        $headers = $row_data[0][0];
        $column_mapping = [];
        foreach ($updated_column as $original_column => $updated_column_data) {

            foreach ($updated_column_data as $updated_name => $config) {
                
                $column_mapping[$original_column] = $updated_name;
            }
        }
        $transformed_headers = array_map(function($header) use ($column_mapping) {
            
            return $column_mapping[strtolower(str_replace(' ', '_', str_replace(['(', ')', '?','/'], '', rtrim($header))))] ?? strtolower(str_replace(' ', '_', str_replace(['(', ')', '?','/'], '', rtrim($header))));
        }, $headers);
        $row_data[0][0] = $transformed_headers;
        foreach ($row_data[0] as $index => $data_row) {
            
            $transformed_data = [];
            foreach ($data_row as $key => $value) {
                
                $original_column = $headers[$key];
                $updated_column_name = $column_mapping[$original_column] ?? $original_column;
                if (isset($updated_column[strtolower(str_replace(' ', '_', str_replace(['(', ')', '?','/'], '', rtrim($original_column))))])) {
                    
                    $transformed_data[] = $value;
                }
            }
            $row_data[0][$index] = $transformed_data;
        }
        
        return $row_data;
    }
        
    public function transformColumns($columns) {
        
        $transformedColumns = [];
        
        foreach ($columns as $key => $value) {

            $parts = explode("::", $value);
            $field = $parts[0];
            $type = isset($parts[1]) ? intval($parts[1]) : null;
            $transformedColumns[$key] = [
                $field => [
                    "status" => true,
                    "type" => $type,
                ]
            ];
        }

        return $transformedColumns;
    }

    public function retrieveContacts($type, $contact_groups, $group_logic = null, $meta_name = null, $logic = null, $logic_range = null, $user_id = null, $custom_gateway = null) {

        $meta_data = [];
        $contact = Contact::query();
        $contact->active()
                    ->whereIn('group_id', $contact_groups);
        
        if ($group_logic) {
        
            if (strpos($meta_name, "::") !== false) {
                
                $attributeParts = explode("::", $meta_name);
                $attributeType  = $attributeParts[1];
                
                if ($attributeType == ContactAttributeEnum::DATE->value) {

                    $startDate = Carbon::parse($logic);
        
                    if ($logic_range) {

                        $endDate = Carbon::parse($logic_range);
                        $contact = $contact->get()->filter(function ($contact) use ($startDate, $endDate, $attributeParts) {

                            $attr = Carbon::parse($contact->meta_data->{$attributeParts[0]}->value);
                            return $attr->between($startDate, $endDate);
                        });
                    } else {

                        $contact = $contact->get()->filter(function ($contact) use ($startDate, $attributeParts) {

                            $attr = Carbon::parse($contact->meta_data->{$attributeParts[0]}->value);
                            return $attr->isSameDay($startDate);
                        });
                    }
                } elseif ($attributeType == ContactAttributeEnum::BOOLEAN->value) {

                    $logicValue = filter_var($logic, FILTER_VALIDATE_BOOLEAN);
                    $contact    = $contact->get()->filter(function ($contact) use ($attributeParts, $logicValue) {

                        $attrValue = filter_var($contact->meta_data->{$attributeParts[0]}->value, FILTER_VALIDATE_BOOLEAN);
                        return $attrValue === $logicValue;
                    });

                } elseif ($attributeType == ContactAttributeEnum::NUMBER->value) { 

                    $numericLogic = filter_var($logic, FILTER_VALIDATE_FLOAT);
                
                    if ($logic_range) {

                        $numericRange = filter_var($logic_range, FILTER_VALIDATE_FLOAT);
                        $contact      = $contact->get()->filter(function ($contact) use ($attributeParts, $numericLogic, $numericRange) {

                            $attrValue = filter_var($contact->meta_data->{$attributeParts[0]}->value, FILTER_VALIDATE_FLOAT);
                            return $attrValue >= $numericLogic && $attrValue <= $numericRange;
                        });
                    } else {

                        $contact = $contact->get()->filter(function ($contact) use ($attributeParts, $numericLogic) {

                            $attrValue = filter_var($contact->meta_data->{$attributeParts[0]}->value, FILTER_VALIDATE_FLOAT);
                            return $attrValue == $numericLogic;
                        });
                    }
                } elseif ($attributeType == ContactAttributeEnum::TEXT->value) { 

                    $textLogic = $logic;
                    $contact   = $contact->get()->filter(function ($contact) use ($attributeParts, $textLogic) {

                        $attrValue = $contact->meta_data->{$attributeParts[0]}->value;
                        return stripos($attrValue, $textLogic) !== false;
                    });
                }
            } else {
                $contact->where($meta_name, 'like', "%$logic%");
            }

            
        }
        
        if (!is_null($user_id)) {

            $contact->where('user_id', $user_id);
        } else {

            $contact->whereNull('user_id');
        }
        if ($type) {

            $allContactNumber[] = $contact->pluck("$type".'_contact')->toArray();
            $numberGroupName    = $contact->pluck('first_name', "$type".'_contact')->toArray();
            $contact_ids        = $contact->pluck('id', "$type".'_contact')->toArray();
            $contact_uids       = $contact->pluck('uid', "$type".'_contact')->toArray();

            foreach ($allContactNumber[0] as $number) {
                
                $meta_data[] = [

                    'contact'                  => $number,
                    'first_name'               => $numberGroupName[$number] ?? null,
                    'id'                       => $contact_ids[$number] ?? null,
                    'uid'                      => $contact_uids[$number] ?? null,
                    'custom_gateway_parameter' => $custom_gateway
                ];
            }
        }
        if(count($meta_data) == 0) throw new ApplicationException(message: translate("No valid contacts were found"));
        return $meta_data;
    }
}