<?php

namespace App\Http\Controllers\User\Contact;

use App\Exceptions\ApplicationException;
use App\Models\Group;
use App\Models\Contact;
use Illuminate\View\View;
use App\Traits\ModelAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\ContactGroupRequest;
use App\Models\ContactGroup;
use App\Services\System\Contact\ContactService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ContactGroupController extends Controller
{
    use ModelAction;

    public ContactService $contactService;

    public function __construct(ContactService $contactService) { 

        $this->contactService = $contactService;
    }

    /**
     * @return \Illuminate\View\View
     * 
     */
    public function index($id = null):View {
        
        Session::put("menu_active", true);
        $user = auth()->user();
        return $this->contactService->getContactGroups($id, $user); 
    }

    /**
     * store
     *
     * @param ContactGroupRequest $request
     * 
     * @return RedirectResponse
     */
    public function store(ContactGroupRequest $request): RedirectResponse {
        
        try {

            $data = $request->all();
            unset($data["_token"]);
            $user = auth()->user();
            return $this->contactService->saveContactGroups(data: $data, user: $user);

        } catch (ApplicationException $e) {
            
            $notify[] = ["error", translate($e->getMessage())];
            return back()->withNotify($notify);

        } catch (Exception $e) {
            
            $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
            return back()->withNotify($notify);
        }
    }

    /**
     * update
     *
     * @param ContactGroupRequest $request
     * @param string $uid
     * 
     * @return RedirectResponse
     */
    public function update(ContactGroupRequest $request, string $uid): RedirectResponse {
        
        try {

            $data = $request->all();
            unset($data["_token"]);
            $user = auth()->user();
            return $this->contactService->saveContactGroups(data: $data, uid: $uid, user: $user);

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
    public function updateStatus(Request $request): string
    {
        try {
            $user = auth()->user();
            $this->validateStatusUpdate(
                request: $request,
                tableName: 'contact_groups', 
                keyColumn: 'uid'
            );

            $notify = $this->statusUpdate(
                request: $request->except('_token'),
                actionData: [
                    'message'               => translate('Group status updated successfully'),
                    'model'                 => new ContactGroup,
                    'column'                => $request->input('column'),
                    'filterable_attributes' => [
                        'user_id'   => $user->id,
                        'uid'       => $request->input('uid')
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
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, string $uid): RedirectResponse {
        
        
        try {

            $data = $request->all();
            unset($data["_token"]);
            $user = auth()->user();
            return $this->contactService->deleteContactGroup(data: $data, uid: $uid, user: $user);

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
    public function bulk(Request $request): RedirectResponse {

        try {

            $user = auth()->user();
            return $this->bulkAction($request, null,[
                "model" => new ContactGroup(),
                'filterable_attributes' => [
                    'user_id'   => $user->id,
                ],
            ]);
        } catch (Exception $e) {
            
            $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
            return back()->withNotify($notify);
        }
    }


    //todo: Update after contacts
    public function fetch(Request $request, $type = null) {

        try {
            
            if ($type == "meta_data") {

                $groupIds = $request->input('group_ids');
                $channel = $request->input('channel');
               
                if($groupIds) {

                    $contacts = Contact::where("user_id", auth()->user()->id)
                                            ->whereIn('group_id', $groupIds)
                                            ->where($channel.'_contact', '!=', '')
                                            ->get();

                    if ($contacts->isNotEmpty()) {

                        $groupAttributes = ContactGroup::whereIn('id', $groupIds)
                            ->whereNotNull('meta_data')
                            ->pluck('meta_data');
            
                        $mergedAttributes = [];
            
                        foreach ($groupAttributes as $attributes) {
                            $decodedAttributes = json_decode($attributes, true);
            
                            foreach ($decodedAttributes as $key => $attribute) {
    
                                if ($attribute['status'] === true) {
    
                                    if (!isset($mergedAttributes[$key]) || $mergedAttributes[$key] !== $attribute['type']) {
                                        $mergedAttributes[$key] = $attribute['type'];
                                    }
                                }
                            }
                        }
                        return response()->json(['status' => true, 'merged_attributes' => $mergedAttributes]);
                    } else {
    
                        return response()->json(['status' => false, 'message' => "No $channel contacts found for the selected groups"]);
                    }
                }
                else {
                    return response()->json(['status' => false, 'message' => translate("No groups are selected")]);
                }
            }
            
        } catch (\Exception $e) {
            
            $notify[] = ['error', translate('Something Went Wrong')];
            return back()->withNotify($notify);
        }
        
    }
}
