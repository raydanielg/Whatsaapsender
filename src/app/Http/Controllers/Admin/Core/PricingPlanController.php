<?php

namespace App\Http\Controllers\Admin\Core;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PricingPlanRequest;
use App\Models\AndroidApi;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Traits\ModelAction;
use App\Service\Admin\Core\PricingPlanService;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;

class PricingPlanController extends Controller
{
    use ModelAction;
    public $pricingPlanService;
    public function __construct()
    {

        $this->pricingPlanService = new PricingPlanService();
    }

    /**
     * 
     * @return \Illuminate\View\View
     * 
     */
    public function index()
    {
        Session::put("menu_active", true);
        $title = translate("Membership Plan List");
        $plans = $this->pricingPlanService->planLog();
        return view('admin.membership.plan.index', compact('title', 'plans'));
    }

    /**
     *
     * @param Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function bulk(Request $request): RedirectResponse
    {

        $status  = 'success';
        $message = translate("Successfully Performed bulk action");
        try {

            list($status, $message) = $this->bulkAction($request, 'recommended_status', [
                "model" => new PricingPlan(),
            ]);
        } catch (\Exception $exception) {

            $status  = 'error';
            $message = translate("Server Error: ") . $exception->getMessage();
        }

        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }


    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws ValidationException If the validation fails.
     * 
     */
    public function statusUpdate(Request $request)
    {

        try {

            $this->validate($request, [

                'id'     => 'required',
                'value'  => 'required',
                'column' => 'required',
            ]);

            $notify = $this->pricingPlanService->statusUpdate($request);
            return $notify;
        } catch (ValidationException $validation) {

            return json_encode([

                'status'  => false,
                'message' => $validation->errors()
            ]);
        }
    }

    /**
     * 
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {

        $status  = 'error';
        $message = 'Something went wrong';

        try {
            list($status, $message) = $this->pricingPlanService->deletePlan($request->input('id'));
        } catch (\Exception $e) {

            $status  = 'error';
            $message = translate("Server Error");
        }
        $notify[] = [$status, $message];
        return back()->withNotify($notify);
    }


    /**
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(): View
    {
        Session::put("menu_active", true);
        $title            = translate("Add A New Membership Plan");
        $mail_credentials = array_keys(config('setting.gateway_credentials.email'));
        $sms_credentials  = array_keys(config('setting.gateway_credentials.sms'));
        unset($sms_credentials[0]);
        $sms_credentials = array_values($sms_credentials);
        $sms_gateways = Gateway::whereNull('user_id')
            ->whereNotNull('sms_gateways')
            ->orderBy('is_default', 'DESC')
            ->pluck('name', 'id')
            ->toArray();
        $android_gateways = AndroidApi::where('admin_id', auth()->guard('admin')->user()->id)
                                ->orderBy('id', 'DESC')
                                ->pluck('name', 'id')
                                ->toArray();
        return view('admin.membership.plan.create', compact('title', 'sms_credentials', 'mail_credentials', 'sms_gateways', 'android_gateways'));
    }

    public function store(Request $request)
    {
        try {
            $validations = [
                'name'                 => 'required|max:255',
                'description'          => 'nullable',
                'amount'               => 'required|numeric|min:0',
                'allow_carry_forward'  => 'nullable',
                'duration'             => 'required|integer|gte:0',
            ];
    
            if ($request->input('allow_admin_creds')) {
                $additionalValidations = [
                    'whatsapp_device_limit'        => ['requiredIf:allow_whatsapp,true', 'gte:0'],
                ];
            } else {
    
                $additionalValidations = [
                    'user_android_gateway_limit' => ['requiredIf:allow_user_android,true', 'gte:0'],
                    'user_whatsapp_device_limit' => ['requiredIf:allow_user_whatsapp,true', 'gte:0'],
                    'mail_gateways'              => ['requiredIf:mail_multi_gateway,true'],
                    'total_mail_gateway'         => ['requiredIf:mail_multi_gateway,true|array'],
                    'total_mail_gateway.*'       => ['numeric', 'gte:1'],
                    'sms_gateways'               => ['requiredIf:sms_multi_gateway,true'],
                    'total_sms_gateway'          => ['requiredIf:sms_multi_gateway,true|array'],
                    'total_sms_gateway.*'        => ['gte:1', 'numeric']
                ];
            }
            $validations = array_merge($validations, $additionalValidations);
            $data = $this->validate($request, $validations);
            $planMapping = config("planaccess.pricing_plan");
    
            foreach ($planMapping as $plan_key => $plan_value) {
                if ($request->input('allow_admin_creds')) {
                    $data["type"] = StatusEnum::TRUE->status();
                    switch ($plan_key) {
                        case ("sms"):
                            $plan_value["is_allowed"] = (bool)$request->input("allow_admin_" . $plan_key) ?? false;
                            unset($plan_value["gateway_limit"]);
                            if (array_key_exists("android", $plan_value)) {
                                $plan_value["android"]["is_allowed"] =  (bool)$request->input("allow_admin_android") ?? false;
                                unset($plan_value["android"]["gateway_limit"]);
                            }
                            break;
                        case ("email"):
                            $plan_value["is_allowed"] = (bool)$request->input("allow_admin_" . $plan_key) ?? false;
                            unset($plan_value["gateway_limit"]);
                            break;
                        case ("whatsapp"):
                            $plan_value["is_allowed"] = (bool)$request->input("allow_admin_" . $plan_key) ?? false;
    
                            $plan_value["gateway_limit"] = (int)$request->input("whatsapp_device_limit");
                            break;
                    }
                    $plan_value["credits"] = (int)$request->input($plan_value["credits"] . "_admin");
                    $plan_value["credits_per_day"] = (int)$request->input($plan_value["credits_per_day"] . "_admin");
                    unset($plan_value["allowed_gateways"]);
                    $planMapping[$plan_key] = $plan_value;
                } else {
                    $data["type"] = StatusEnum::FALSE->status();
                    if ($plan_key == "sms") {
                        if ($request->input("sms_multi_gateway")) {
                            $plan_value["is_allowed"] = (bool)$request->input("sms_multi_gateway");
                            for ($i = 0; $i < count($data['sms_gateways']); $i++) {
    
                                $multi['sms'][$data['sms_gateways'][$i]] = (int)$data['total_sms_gateway'][$i];
                            }
                            unset($data['sms_gateways']);
                            unset($data['total_sms_gateway']);
                            $plan_value["gateway_limit"] = array_sum(array_values($multi["sms"]));
                            $plan_value["allowed_gateways"] = $multi["sms"];
                        } else {
                            $plan_value["is_allowed"] = false;
                            unset($plan_value["gateway_limit"]);
                            unset($plan_value["allowed_gateways"]);
                        }
                        if ($request->input("allow_user_android")) {
                            $plan_value["android"]["is_allowed"] = (bool)$request->input("allow_user_android");
                            $plan_value["android"]["gateway_limit"] = (int)$request->input("user_android_gateway_limit");
                            unset($data['user_android_gateway_limit']);
                        } else {
                            $plan_value["android"]["is_allowed"] = false;
                            unset($plan_value["android"]["gateway_limit"]);
                        }
                    }
                    if ($plan_key == "email") {
                        if ($request->input("mail_multi_gateway")) {
                            $plan_value["is_allowed"] = (bool)$request->input("mail_multi_gateway");
                            for ($i = 0; $i < count($data['mail_gateways']); $i++) {
    
                                $multi['mail'][$data['mail_gateways'][$i]] = (int)$data['total_mail_gateway'][$i];
                            }
                            unset($data['mail_gateways']);
                            unset($data['total_mail_gateway']);
                            $plan_value["gateway_limit"] = array_sum(array_values($multi["mail"]));
                            $plan_value["allowed_gateways"] = $multi["mail"];
                        } else {
                            $plan_value["is_allowed"] = false;
                            unset($plan_value["gateway_limit"]);
                            unset($plan_value["allowed_gateways"]);
                        }
                    }
                    if ($plan_key == "whatsapp") {
    
                        if ($request->input("allow_user_whatsapp")) {
                            $plan_value["is_allowed"] = (bool)$request->input("allow_user_whatsapp");
                            $plan_value["gateway_limit"] = (int)$request->input("user_whatsapp_device_limit");
                            unset($data['user_whatsapp_device_limit']);
                        } else {
    
                            $plan_value["is_allowed"] = false;
                            unset($plan_value["gateway_limit"]);
                        }
                    }
                    $plan_value["credits"] = (int)$request->input($plan_value["credits"] . "_user");
                    $plan_value["credits_per_day"] = (int)$request->input($plan_value["credits_per_day"] . "_user");
    
                    $planMapping[$plan_key] = $plan_value;
                }
            }
    
            $data["carry_forward"] = $request->input("allow_carry_forward") ? StatusEnum::TRUE->status() : StatusEnum::FALSE->status();
    
            $data = array_merge($data, $planMapping);
    
            PricingPlan::create($data);
            $notify[] = ['success', translate('Pricing plan has been created')];
            return back()->withNotify($notify);
        } catch (\Exception $e) {

            $notify[] = ['error', translate('Server Error')];
            return back()->withNotify($notify);
        }
    }

    private function allowedGateway($request, $mapping): array
    {
        $allowedGateway = [];
        foreach ($mapping as $inputKey => $outputKey) {

            $value = $request[$inputKey] ?? false;
            $this->setNestedArrayValue($allowedGateway, $outputKey, $value);
        }
        return $allowedGateway;
    }
    private function setNestedArrayValue(&$array, $key, $value)
    {
        $keys = explode('.', $key);

        foreach ($keys as $nestedKey) {
            if (!isset($array[$nestedKey])) {
                $array[$nestedKey] = [];
            }

            $array = &$array[$nestedKey];
        }

        if ($value === 'true') {
            $array = true;
        } elseif ($value === 'false') {
            $array = false;
        } else {
            $array = $value;
        }
    }
    public function edit($id)
    {

        Session::put("menu_active", true);
        $mail_credentials = array_keys(config('setting.gateway_credentials.email'));
        $sms_credentials  = array_keys(config('setting.gateway_credentials.sms'));
        unset($sms_credentials[0]);
        $sms_credentials  = array_values($sms_credentials);
        $plan             = PricingPlan::findOrFail($id);
        $title            = translate("Update ") . $plan->name . translate(" Subscription Plan");
        $mail_gateways    = $plan->type == StatusEnum::FALSE->status() && @$plan->email->allowed_gateways ? $plan->email->allowed_gateways : null;
        $sms_gateways     = $plan->type == StatusEnum::FALSE->status() && @$plan->sms->allowed_gateways ? $plan->sms->allowed_gateways : null;

        return view('admin.membership.plan.edit', compact('title', 'sms_credentials', 'mail_credentials', 'plan', 'sms_gateways', 'mail_gateways'));
    }

    public function update(PricingPlanRequest $request) {

        try {
            $data = $request->validated();
            $planMapping = config("planaccess.pricing_plan");

            foreach ($planMapping as $plan_key => $plan_value) {
                if ($request->input('allow_admin_creds')) {
                    $data["type"] = StatusEnum::TRUE->status();
                    switch ($plan_key) {
                        case ("sms"):
                            $plan_value["is_allowed"] = (bool)$request->input("allow_admin_" . $plan_key) ?? false;
                            unset($plan_value["gateway_limit"]);
                            if (array_key_exists("android", $plan_value)) {
                                $plan_value["android"]["is_allowed"] =  (bool)$request->input("allow_admin_android") ?? false;
                                unset($plan_value["android"]["gateway_limit"]);
                            }
                            break;
                        case ("email"):
                            $plan_value["is_allowed"] = (bool)$request->input("allow_admin_" . $plan_key) ?? false;
                            unset($plan_value["gateway_limit"]);
                            break;
                        case ("whatsapp"):
                            $plan_value["is_allowed"] = (bool)$request->input("allow_admin_" . $plan_key) ?? false;

                            $plan_value["gateway_limit"] = (int)$request->input("whatsapp_device_limit");
                            break;
                    }
                    $plan_value["credits_per_day"] = (int)$request->input($plan_value["credits_per_day"] . "_admin");
                    $plan_value["credits"] = (int)$request->input($plan_value["credits"] . "_admin");
                    unset($plan_value["allowed_gateways"]);
                    $planMapping[$plan_key] = $plan_value;
                } else {
                    $data["type"] = StatusEnum::FALSE->status();

                    if ($plan_key == "sms") {
                        if ($request->input("sms_multi_gateway")) {
                            $plan_value["is_allowed"] = (bool)$request->input("sms_multi_gateway");
                            for ($i = 0; $i < count($data['sms_gateways']); $i++) {

                                $multi['sms'][$data['sms_gateways'][$i]] = (int)$data['total_sms_gateway'][$i];
                            }
                            unset($data['sms_gateways']);
                            unset($data['total_sms_gateway']);
                            $plan_value["gateway_limit"] = array_sum(array_values($multi["sms"]));
                            $plan_value["allowed_gateways"] = $multi["sms"];
                        } else {
                            $plan_value["is_allowed"] = false;
                            unset($plan_value["gateway_limit"]);
                            unset($plan_value["allowed_gateways"]);
                        }
                        if ($request->input("allow_user_android")) {
                            $plan_value["android"]["is_allowed"] = (bool)$request->input("allow_user_android");
                            $plan_value["android"]["gateway_limit"] = (int)$request->input("user_android_gateway_limit");
                            unset($data['user_android_gateway_limit']);
                        } else {
                            $plan_value["android"]["is_allowed"] = false;
                            unset($plan_value["android"]["gateway_limit"]);
                        }
                    }
                    if ($plan_key == "email") {
                        if ($request->input("mail_multi_gateway")) {
                            $plan_value["is_allowed"] = (bool)$request->input("mail_multi_gateway");
                            for ($i = 0; $i < count($data['mail_gateways']); $i++) {

                                $multi['mail'][$data['mail_gateways'][$i]] = (int)$data['total_mail_gateway'][$i];
                            }
                            unset($data['mail_gateways']);
                            unset($data['total_mail_gateway']);
                            $plan_value["gateway_limit"] = array_sum(array_values($multi["mail"]));
                            $plan_value["allowed_gateways"] = $multi["mail"];
                        } else {
                            $plan_value["is_allowed"] = false;
                            unset($plan_value["gateway_limit"]);
                            unset($plan_value["allowed_gateways"]);
                        }
                    }
                    if ($plan_key == "whatsapp") {

                        if ($request->input("allow_user_whatsapp")) {
                            $plan_value["is_allowed"] = (bool)$request->input("allow_user_whatsapp");
                            $plan_value["gateway_limit"] = (int)$request->input("user_whatsapp_device_limit");
                            unset($data['user_whatsapp_device_limit']);
                        } else {

                            $plan_value["is_allowed"] = false;
                            unset($plan_value["gateway_limit"]);
                        }
                    }
                    $plan_value["credits_per_day"] = (int)$request->input($plan_value["credits_per_day"] . "_user");
                    $plan_value["credits"] = (int)$request->input($plan_value["credits"] . "_user");
                    $planMapping[$plan_key] = $plan_value;
                }
            }

            $data["carry_forward"] = $request->input("allow_carry_forward") ? StatusEnum::TRUE->status() : StatusEnum::FALSE->status();
            $data = array_merge($data, $planMapping);
            
            $plan = PricingPlan::findOrFail($request->id);
            $plan->update($data);
            $this->pricingPlanService->updatePlanRelatedModels($plan->id);
            $notify[] = ['success', translate('Pricing plan has been updated')];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            
            $notify[] = ['error', translate('Server Error')];
            return back()->withNotify($notify);
        }
    }
}
