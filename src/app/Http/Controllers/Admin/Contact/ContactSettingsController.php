<?php

namespace App\Http\Controllers\Admin\Contact;

use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use App\Exceptions\ApplicationException;
use App\Service\Admin\Core\SettingService;
use App\Http\Requests\ContactSettingsRequest;
use Illuminate\Validation\ValidationException;
use App\Services\System\Contact\ContactService;

class ContactSettingsController extends Controller
{
    protected SettingService $settingService;
    protected ContactService $contactService;

    public function __construct(SettingService $settingService, ContactService $contactService)
    {
        $this->settingService = $settingService;
        $this->contactService = $contactService;
    }

    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        Session::put("menu_active", true);
        return $this->contactService->getContactAttributes();
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
            $data = $this->contactService->saveContactAttributes($data);
            
            $this->settingService->updateSettings($data);

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
            $data = $this->contactService->deleteContactAttributes($data);
            $this->settingService->updateSettings($data);
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
            $result = $this->contactService->contactAttributeStatusUpdate($request);
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