<?php

namespace App\Http\Controllers\User\Contact;

use App\Models\Group;
use App\Models\Contact;
use App\Enums\StatusEnum;
use App\Exceptions\ApplicationException;
use Illuminate\View\View;
use App\Traits\ModelAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContactRequest;
use App\Models\ContactGroup;
use Illuminate\Support\Facades\Session;
use App\Service\Admin\Core\FileService;
use App\Services\System\Contact\ContactService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContactController extends Controller
{
    use ModelAction;

    public ContactService $contactService;
    public FileService $fileService;
    public function __construct(ContactService $contactService, FileService $fileService) { 

        $this->contactService = $contactService;
        $this->fileService    = $fileService;
    }

     /**
     * index
     *
     * @param int|string|null $group_id
     * 
     * @return View
     */
    public function index(int|string|null $group_id = null): View {
        
        Session::put("menu_active", true);
        $user = auth()->user();
        return $this->contactService->getContacts($group_id, $user);
    }

    /**
     * exportContacts
     *
     * @param Request $request
     * @param int|string|null $id
     * 
     * @return mixed
     */
    public function exportContacts(Request $request, int|string|null $groupId = null): mixed {

        try {

            return $this->contactService->exportContacts($request, $groupId);
            
        } catch (Exception $e) {
            
            return response()->json([
                "status"  => false, 
                "message" => getEnvironmentMessage($e->getMessage())
            ]);
        }
    }

    /**
     * create
     *
     * @param int|string|null $group_id
     * 
     * @return View
     */
    public function create(int|string|null $group_id = null):View {
        
        Session::put("menu_active", true);
        $user = auth()->user();
        return $this->contactService->createContact($group_id, $user);
    }

    /**
     * store
     *
     * @param ContactRequest $request
     * 
     * @return RedirectResponse
     */
    public function store(ContactRequest $request): RedirectResponse {
        
        try {

            $data = $request->all();
            unset($data["_token"]);
            $user = auth()->user();
            return $this->contactService->contactSave($data, $user);

        } catch (ApplicationException $e) {
            
            $notify[] = ["error", translate($e->getMessage())];
            return back()->withNotify($notify);

        } catch (Exception $e) {
            
            $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
            return back()->withNotify($notify);
        }
    }

   /**
     * updateStatus
     *
     * @param Request $request
     * 
     * @return string
     */
    public function updateStatus(Request $request): string {
        
        try {
            
            $this->validateStatusUpdate(
                request: $request,
                tableName: 'contacts', 
                keyColumn: 'id'
            );
            $user = auth()->user();

            $notify = $this->statusUpdate(
                request: $request->except('_token'),
                actionData: [
                    'message'               => translate('Contact status updated successfully'),
                    'model'                 => new Contact,
                    'column'                => $request->input('column'),
                    'filterable_attributes' => [
                        'id'        => $request->input('id'),
                        'user_id'   => $user->id
                    ],
                    'reload'                => true
                ]
            );

            return $notify;

        } catch (Exception $e) {
            
            return response()->json([
                'status'    => false,
                'message'   => getEnvironmentMessage($e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR); 
        }
    }

    /**
     * destroy
     *
     * @param Request $request
     * @param string $uid
     * 
     * @return RedirectResponse
     */
    public function destroy(Request $request, string $uid): RedirectResponse {
        
        try {

            $data = $request->all();
            unset($data["_token"]);
            $user = auth()->user();
            return $this->contactService->deleteContact($uid, $user);

        } catch (ApplicationException $e) {
            
            $notify[] = ["error", translate($e->getMessage())];
            return back()->withNotify($notify);

        } catch (Exception $e) {
            
            $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
            return back()->withNotify($notify);
        }
    }

    /**
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function bulk(Request $request) :RedirectResponse {

        try {

            $user = auth()->user();
            return $this->bulkAction($request, null,[
                "model" => new Contact(),
                'filterable_attributes' => [
                    'user_id'   => $user->id,
                ],
            ]);

        } catch (Exception $e) {
            
            $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
            return back()->withNotify($notify);
        }
    }

    /**
     * singleEmailVerification
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function singleEmailVerification(Request $request): JsonResponse {

        try {

            $request->validate(['uid' => 'required']);
            $user = auth()->user();
            $result = $this->contactService->singleContactEmailVerification($request, $user);
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


    /**
     * demoFile
     *
     * @param string|null $type
     * 
     * @return BinaryFileResponse
     */
    public function demoFile(?string $type = null):BinaryFileResponse|RedirectResponse {

        try {
            return $this->fileService->generateContactDemo(type: $type, allow_attribute: true);
        } catch (\Exception $e) {
            $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
            return back()->withNotify($notify);
        }
    }

    /**
     * uploadFile
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function uploadFile(Request $request): JsonResponse {

        try {

            return $this->contactService->contactUploadFile($request);
        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => getEnvironmentMessage($e->getMessage()),
            ], Response::HTTP_INTERNAL_SERVER_ERROR); 
        }
    }

    /**
     * deleteFile
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function deleteFile(Request $request): JsonResponse {

        try {

            return $this->fileService->deleteContactFile($request->input('file_name'));

        } catch (Exception $e) {

            return response()->json([
    
                'status'  => false, 
                'message' => getEnvironmentMessage($e->getMessage()),
            ]);
        }
    }

    /**
     * parseFile
     *
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function parseFile(Request $request): JsonResponse {

        try {
            
            return $this->fileService->parseContactFile($request->input('filePath'));

        } catch (Exception $e) {

            return response()->json([
    
                'error' => getEnvironmentMessage($e->getMessage()),
            ]);
        }
    }
}
