<?php
namespace App\Service\Admin\Gateway;

use App\Enums\StatusEnum;
use App\Models\Template;
use App\Models\User;
use App\Models\WhatsappDevice;
use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Http;

class WhatsappGatewayService
{

    public function assignGateway($method, $gatewayId, $metaData, $userId = null) {
        
        $status = 'error';
        $message = translate("No Connected Whatsapp Device Found");
        $gateway = null;
       
        if ($gatewayId != "-1") {
            $gateway = WhatsappDevice::where('id', $gatewayId)
                ->where('status', 'connected')
                ->first();
        } else {
          
            $availableGateways = $this->getAvailableGateways($userId, $method);
            
            if ($availableGateways->isNotEmpty()) {
                if ($this->hasNestedArray($metaData)) {
                    $metaData = $this->assignGatewaysToContacts($metaData, $availableGateways);
                    $gateway = $availableGateways->first();
                } else {
                    $gateway = $availableGateways->first();
                    $metaData['gateway']      = $gateway->type == StatusEnum::TRUE->status() ? 'WhatsApp Cloud API' : 'WhatsApp Node Module';
                    $metaData['gateway_name'] = $gateway->name;
                }
            }
        }

        if ($gateway) {
            $status = 'success';
            $message = translate("Whatsapp message requests are assigned with the gateway");
        } else {
            $message = translate("Could not find a connected device");
        }
        return [$status, $message, $metaData, $gateway];
    }

    private function getAvailableGateways($userId = null, $method) {

        
        $query = WhatsappDevice::where('status', 'connected');
        
        if ($method == "without_cloud_api") {

            $query->where("type", StatusEnum::FALSE->status());
            
        } else {
            $query->where("type", StatusEnum::TRUE->status());
        }

        if ($userId) {

            $user = User::find($userId);
            if ($user) {

                $planAccess = (object) planAccess($user);
                
                if ($planAccess->type == StatusEnum::FALSE->status() || (array_key_exists('is_allowed', $planAccess->whatsapp) ? $planAccess->whatsapp['is_allowed'] : false)) {

                    $query->where("user_id", $userId);
                } else {
                    $query->whereNull("user_id");
                }
            }
        } else {
            $query->whereNull("user_id");
        }
        return $query->get();
    }

    private function assignGatewaysToContacts($contacts, $gateways) {

        $gatewayCount = $gateways->count();
        foreach ($contacts as $index => &$contact) {
            
            $gateway = $gateways[$index % $gatewayCount];
            $contact['gateway']      = $gateway->type == StatusEnum::TRUE->status() ? 'WhatsApp Cloud API' : 'WhatsApp Node Module';
            $contact['gateway_name'] = $gateway->name;
            $contact['gateway_id']  = $gateway->id;
        }
        return $contacts;
    }

    function hasNestedArray(array $array): bool {
        foreach ($array as $value) {
            if (is_array($value)) {
                return true; 
            }
        }
        return false; 
    }

    public function save($request, $user_id = null) {
       
        $whatsapp = null;
        
        if(request()->routeIs('*.gateway.whatsapp.device.save') && $this->serverReady($request)) {
            
            $credentials = $this->prepCredential($request);
            
            if(!$user_id && auth()->guard('admin')->user()) {

                $data = $this->prepData($request, $credentials, auth()->guard('admin')->user()->id);
                
                $whatsapp = WhatsappDevice::updateOrCreate([

                    'id' => $request->input('id')

                ], $data);
            } else {

                $data = $this->prepData($request, $credentials, null, $user_id);
                
                $whatsapp = WhatsappDevice::updateOrCreate([

                    'id' => $request->input('id')

                ], $data);
            }
        } else {
            
            $credentials = request()->routeIs('*.gateway.whatsapp.cloud.api.save') ? $request->input('credentials') : $this->prepCredential($request);

            if(!$user_id && auth()->guard('admin')->user()) {
                
                $data = $this->prepData($request, $credentials, auth()->guard('admin')->user()->id);
               
                $whatsapp = WhatsappDevice::updateOrCreate([

                    'id' => $request->input('id')

                ], $data);
            } else {

                $data = $this->prepData($request, $credentials, null, $user_id);
                
                $whatsapp = WhatsappDevice::updateOrCreate([

                    'id' => $request->input('id')

                ], $data);
            }
        }
        
        return $whatsapp;
    }

    public function prepCredential($request) {
      
        if(!$request->has('id')) {
          
            $data['number']      = ' ';
            $data['min_delay']   = $request->input('min_delay');
            $data['max_delay']   = $request->input('max_delay');
            $data['multidevice'] = 'YES';
        } else {
            $gateway = WhatsappDevice::where('id', $request->input('id'))->first();
            $data = $gateway->credentials;
            $data['min_delay'] = $request->input('min_delay');
            $data['max_delay'] = $request->input('max_delay');
        }
       
        return $data;
    }

    public function prepData($request, $credentials, $id = null, $user_id = null) {
        
        $data = [
            "admin_id"    => $id,
            "user_id"     => $user_id,
            "name"        => request()->routeIs('*.gateway.whatsapp.cloud.api.save') ? $request->input('name') : randomNumber()."_". $request->input('name'),
            "type"        => request()->routeIs('*.gateway.whatsapp.cloud.api.save') ? StatusEnum::TRUE->status() : StatusEnum::FALSE->status(),
            "credentials" => $credentials,
            "status"      => request()->routeIs('*.gateway.whatsapp.cloud.api.save') ? 'connected' : 'initiate'
        ];
        if($request->input('id')) {

            unset($data['admin_id'], $data['user_id'], $data['type'], $data['status'], $data['name']);
        }
        
        return $data;
    }

    public function delete($request) {

        $whatsapp = WhatsappDevice::where('id', $request->input('id'))->first();
        
        if($whatsapp->type == StatusEnum::FALSE->status()) {

            $response = $this->sessionDelete($whatsapp->name);
            $notify[] = ['success', translate('WhatsApp device has been successfully deleted')];

            if ($response->status() == 200) {

                $notify[] = ['success', translate('WhatsApp device has been successfully deleted, and the associated sessions have been cleared from the node')];
            }
            $whatsapp->delete();
        } else {

            Template::where('cloud_id', $request->input('id'))->delete();
            $whatsapp->delete();
            $notify[] = ['success', 'WhatsApp cloud API removed successfully.'];
        }
        return $notify;
    }

    public function updateEnvParam($request) {

        $data = [
            'WP_SERVER_URL'      => "http://$request->server_host:$request->server_port",
            'NODE_SERVER_HOST'   => $request->server_host,
            'NODE_SERVER_PORT'   => $request->server_port,
            'MAX_RETRIES'        => $request->max_retries,
            'RECONNECT_INTERVAL' => $request->reconnect_interval,
        ];
        return $data;
    }

    //Cloud API Service
    

    // Whatsapp Device Related functions 

    public function domain() {

        $currentUrl = request()->root();
        $parsedUrl  = parse_url($currentUrl);
        $domain     = $parsedUrl['host'];
        return $domain;
    }

    public function sessionInit() {

        $response   = Http::post(env('WP_SERVER_URL').'/sessions/init', [ 
            
            'domain' => $this->domain()
        ]);
        $responseBody = json_decode($response->body());

       return [$response, $responseBody];
    }

    public function sessionCreate($whatsapp) {

        $response = Http::post(env('WP_SERVER_URL').'/sessions/create', [
            
            'id'       => $whatsapp->name,
            'isLegacy' => !(array_key_exists("multidevice", $whatsapp->credentials) && $whatsapp->credentials["multidevice"] ? $whatsapp->credentials["multidevice"] : 'NO' === "YES"),
            'domain'   => $this->domain()
        ]);
        
        $responseBody = json_decode($response->body());

        return [
            $response, 
            $responseBody
        ];
    }

    public function sessionStatus($name) {

        $checkConnection = Http::get(env('WP_SERVER_URL').'/sessions/status/'.$name);

        return $checkConnection;
    }

    public function sessionDelete($name) {

        $response = Http::delete(env('WP_SERVER_URL').'/sessions/delete/'.$name);

        return $response;
    }

    public function serverReady() {

        list($response, $responseBody) = $this->sessionInit();
        return $response->status() === 200 && $responseBody->success ? true : false;
    }

    public function checkServerStatus() {

        $checkWhatsappServer = true;
        try {

            $wpUrl = env('WP_SERVER_URL');
            Http::get($wpUrl);

        } catch (\Exception $e) {

            $checkWhatsappServer = false;
        }

        $devices = WhatsappDevice::where('type', StatusEnum::FALSE->status())
                                    ->orderBy('id', 'desc')
                                    ->get();

        foreach ($devices as $value) {

            try {

                $sessions               = $this->sessionStatus($value->name);
                $whatsAppDevice         = WhatsappDevice::where('id', $value->id)->first();
                $whatsAppDevice->status = 'disconnected';
                if ($sessions->status() == 200) {

                    $whatsAppDevice->status = 'connected';
                }
                $whatsAppDevice->save();
            } catch (\Exception $e) {

                $checkWhatsappServer = false;
            }
        }
        return $checkWhatsappServer;
    }

    public function deviceStatusUpdate($request, $id, $user_id = null) {

        if($user_id) {
            $whatsapp = WhatsappDevice::where('user_id', $user_id)
                                        ->where('type', StatusEnum::FALSE->status())
                                        ->where('id', $request->input('id'))
                                        ->first();
        } else {
            $whatsapp = WhatsappDevice::where('admin_id', $id)
                                    ->where('type', StatusEnum::FALSE->status())
                                    ->where('id', $request->input('id'))
                                    ->first();
        }
        

        list($whatsapp, $message) = $this->sessionStatusUpdate($whatsapp, $request->input('status'));

        $whatsapp->update();
        return $message;
    }

    public function sessionStatusUpdate($whatsapp, $value) {

        $status  = false;
        $message = translate("Something went wrong");
        switch ($value) {

            case 'connected':

                $session = $this->sessionStatus($whatsapp->name);
                if ($session->status() == 200) {

                    $status = true;
                    $message = translate("Successfully whatsapp sessions reconnect");
                } else {
                    
                    $this->sessionDelete($whatsapp->name);
                    $message = translate("Successfully whatsapp sessions disconnected");
                }
                break;

            case 'disconnected':

                $session = $this->sessionDelete($whatsapp->name);
                if ($session->status() == 200) {

                    $message = translate('Whatsapp Device successfully Deleted');
                } else {

                    $message = translate('Opps! Something went wrong, try again');
                }
                break;

            default:

                $session = $this->sessionDelete($whatsapp->name);
                if ($session->status() == 200) {

                    $message = translate('Whatsapp Device successfully Deleted');
                } else {

                    $message = translate('Opps! Something went wrong, try again');
                }
                break;
        }

        $whatsapp->status = $status ? 'connected' : 'disconnected';

        return [
            $whatsapp,
            $message
        ];
    }
}
