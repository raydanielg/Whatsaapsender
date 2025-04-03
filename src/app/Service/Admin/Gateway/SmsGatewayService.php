<?php
namespace App\Service\Admin\Gateway;

use App\Models\Gateway;
use App\Enums\StatusEnum;
use App\Models\AndroidApi;
use App\Models\AndroidApiSimInfo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SmsGatewayService
{
    public function save($request, $user_id = null) {
        
        $data = $this->param($request, $user_id);

        $gateway = Gateway::updateOrCreate([

            'id' => $request->input('id'),
            
        ], $data);

        return $gateway;
    }

    public function param($request, $user_id) {

        $data = [
            'user_id'       => $user_id,
            'type' 			=> $request->input('type'),
            'name' 			=> $request->input('name'),
            'sms_gateways'  => $request->input('driver_information'),
            'mail_gateways' => null
        ];
        return $data;
    }

    public function statusUpdate($request, $user_id = null) {
        
        try {
            $status   = true;
            $reload   = false;
            $message  = translate('Gateway status updated successfully');
            $gateway = Gateway::whereNotNull("sms_gateways")->where("user_id", $user_id)->where("id",$request->input('id'))->first();
            $column   = $request->input("column");
            
            if($column != "is_default" && $request->value == StatusEnum::TRUE->status()) {
                
                $gateway->status = StatusEnum::FALSE->status();
                if($gateway->is_default == StatusEnum::TRUE->status()) {

                    $gateway->is_default = StatusEnum::FALSE->status();
                    $reload = true;
                }
                $gateway->update();

            } elseif($column != "is_default" && $request->value == StatusEnum::FALSE->status()) {

                $reload = true;
                $gateway->status = StatusEnum::TRUE->status();
                $gateway->update();

            } elseif($column == "is_default") {
                
                $reload = true;
                $message  = translate('Default gateway updated successfully');
                Gateway::whereNotNull("sms_gateways")->where("user_id", $user_id)->where('id', '!=',$request->id)->update(["is_default" => StatusEnum::FALSE->status()]);
                $gateway->$column = StatusEnum::TRUE->status();
                $gateway->status  = StatusEnum::TRUE->status();
                
                $gateway->update();
            } else {

                $status = false;
                $message = translate("Something went wrong while updating this gateway");
            }

        } catch (\Exception $error) {

            $status  = false;
            $message = $error->getMessage();
        }

        return json_encode([
            'reload'  => $reload,
            'status'  => $status,
            'message' => $message
        ]);
    }

    public function assignGateway($method, $gateway_id, $meta_data, $sms_type, $campaign_name = null, $user_id = null) {
      
        $status  = 'error';
        $message = translate("No Default SMS Gateway Found");
        $plan_access = [];
        $gateway    = [];
        if($method == StatusEnum::FALSE->status()) {
           

            if($user_id) {

                $user = User::where("id", $user_id)->first();
                if($user) {

                    $plan_access = (object) planAccess($user);
                    if($plan_access->type == StatusEnum::FALSE->status()) {

                        $gateway = $gateway_id == -1 
                        ? AndroidApi::where("user_id", $user_id)->where('status', StatusEnum::TRUE->status())->inRandomOrder()->first()?->simInfo()->first() 
                        : AndroidApiSimInfo::where('id', $gateway_id)->first();
                    } else {

                        $gateway = $gateway_id == -1 
                        ? AndroidApi::whereNull("user_id")->where('status', StatusEnum::TRUE->status())->inRandomOrder()->first()?->simInfo()->first() 
                        : AndroidApiSimInfo::where('id', $gateway_id)->first();
                    }
            
                }
            } else {

                $gateway = $gateway_id == -1 
                ? AndroidApi::whereNull("user_id")->where('status', StatusEnum::TRUE->status())->inRandomOrder()->first()?->simInfo()->first() 
                : AndroidApiSimInfo::where('id', $gateway_id)->first();
            }
           
            
            if ($gateway) {

                $status = 'success';
                $message = translate("SMS request are assigned with the gateway");
                $gatewayName = $gateway->sim_number;
                $gatewayType = $gateway->androidGateway->name;
                
                if($this->hasNestedArray($meta_data)) {
                    
                    foreach ($meta_data as &$contact) {
                        
                        if($campaign_name) {

                            $contact["campaign_name"] = $campaign_name;
                        } 
                       
                        $contact['gateway'] = $gatewayType;
                        $contact['gateway_name'] = $gatewayName;
                        $contact['sms_type'] = $sms_type;
                    }
                } else {
                    
                    $meta_data['gateway'] = $gatewayType;
                    $meta_data['gateway_name'] = $gatewayName;
                    $meta_data['sms_type'] = $sms_type;
                }
            } else {

                $status = "error";
                $message = translate("No active sims were found");
            }

        } else {
            
            if($user_id) {

                $user = User::where("id", $user_id)->first();
                if($user) {

                    $plan_access = (object) planAccess($user);
                    if($plan_access->type == StatusEnum::FALSE->status()) {

                        $gateway = $gateway_id == -1 ? Gateway::where("user_id", $user_id)->where('is_default', StatusEnum::TRUE->status())->where('status', StatusEnum::TRUE->status())->whereNotNull('sms_gateways')->first() : Gateway::where("user_id", $user_id)->where('status', StatusEnum::TRUE->status())->where('id', $gateway_id)->whereNotNull('sms_gateways')->first();
                    } else {

                        $gateway = $gateway_id == -1 ? Gateway::where('is_default', StatusEnum::TRUE->status())->whereNull("user_id")->whereNotNull('sms_gateways')->first() : Gateway::where('id', $gateway_id)->whereNotNull('sms_gateways')->first();
                    }
            
                }
            } else {

                $gateway = $gateway_id == -1 ? Gateway::where('is_default', StatusEnum::TRUE->status())->whereNull("user_id")->whereNotNull('sms_gateways')->first() : Gateway::where('id', $gateway_id)->whereNotNull('sms_gateways')->first();
            }

            if ($gateway) {
                
                $status = 'success';
                $message = translate("SMS request are assigned with the gateway");
                $gatewayName = $gateway->name;
                $gatewayType = transformToCamelCase($gateway->type);
                if($this->hasNestedArray($meta_data)) {

                    foreach ($meta_data as &$contact) {
                    
                        $contact['gateway'] = $gatewayType;
                        $contact['gateway_name'] = $gatewayName;
                        $contact['sms_type'] = $sms_type;
                    }
                } else {

                    $meta_data['gateway'] = $gatewayType;
                    $meta_data['gateway_name'] = $gatewayName;
                    $meta_data['sms_type'] = $sms_type;
                }
            } else {

                $status = "error";
                $message = translate("No default gateways were found");
            }
        }
        return [$status, $message, $meta_data, $gateway ? $gateway : null];
    }

    function hasNestedArray(array $array): bool {
        
        foreach ($array as $value) {
            if (is_array($value)) {
                return true; 
            }
        }
        return false; 
    }

    public function apiAssignGateway($identifier) {
        $androidGateway = AndroidApi::where('name', $identifier)->first();
        
        if ($androidGateway) {
            return $androidGateway->simInfo()->inRandomOrder()->first();
        }
        $smsGateway = Gateway::where('uid', $identifier)->first();
        if ($smsGateway) {
            return $smsGateway;
        }
        return null;
    }
}
