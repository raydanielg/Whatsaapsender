<?php

namespace App\Http\Controllers\User\Contact;

use Exception;
use App\Enums\StatusEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Service\Admin\Core\SettingService;
use App\Http\Requests\ContactSettingsRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ApplicationException;
use App\Service\Admin\Core\CollectionService;
use App\Service\Admin\Contact\ContactSettingsService;
use App\Services\System\Contact\ContactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ContactSettingsController extends Controller
{
    protected SettingService $settingService;
    protected ContactService $contactService;

    public function __construct(SettingService $settingService, ContactService $contactService) { 

        $this->contactService = $contactService;
        $this->settingService = $settingService;
        
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function index(): View {

        Session::put("menu_active", true);
        return $this->contactService->getContactAttributes(user: auth()->user());
    }

    /**
     * store
     *
     * @param ContactSettingsRequest $request
     * 
     * @return RedirectResponse
     */
    public function store(ContactSettingsRequest $request): RedirectResponse
    {
        try {

            $data = $request->except('_token');
            $user = auth()->user();
            $data = $this->contactService->saveContactAttributes($data, $user);
            
            $user->contact_meta_data = json_encode(Arr::get($data, "contact_meta_data"));
            $user->update();

            $message = $request->has('old_attribute_name')
                                    ? translate("Contact attribute updated successfully")
                                    : translate("New contact attribute added");

            return back()->withNotify(['success', $message]);

        } catch (\Exception $e) {
            
            return back()->withNotify(['error', getEnvironmentMessage($e->getMessage())]);
        }
    }

    /**
     * destroy
     *
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {

            $data = $request->except('_token');
            $user = auth()->user();
            $data = $this->contactService->deleteContactAttributes($data, $user);
            $user->contact_meta_data = json_encode(Arr::get($data, "contact_meta_data"));
            $user->update();
            return back()->withNotify(['success', translate("Contact Attribute deleted")]);

        } catch (\Exception $e) {

            return back()->withNotify(['error', getEnvironmentMessage($e->getMessage())]);
        }
    }

    /**
     * Update the status of a contact attribute.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statusUpdate(Request $request): JsonResponse
    {
        try {

            $request->validate(['name' => 'required']);
            $user = auth()->user();
            $result = $this->contactService->contactAttributeStatusUpdate($request, $user);
            return $result; 

        } catch (ValidationException $e) {
            
            return response()->json([
                'status' => false,
                'message' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY); 

        } catch (ApplicationException $e) {
            
            return response()->json([
                'status' => false,
                'message' => translate($e->getMessage()),
            ], $e->getStatusCode()); 

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => getEnvironmentMessage($e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR); 
        }
    }
}
