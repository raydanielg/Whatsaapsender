<?php
namespace App\Service\Admin\Template;

use App\Enums\ServiceType;
use App\Models\Gateway;
use App\Enums\StatusEnum;
use App\Enums\TemplateProvider;
use App\Models\Template;
use App\Models\WhatsappDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TemplateService
{
    public function getUserTemplate() {

        return Template::search(['name'])
                        ->whereNotNull('user_id')
                        ->where('plugin', StatusEnum::FALSE->status())
                        ->routefilter()
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }
    public function getUserSpecificTemplate($user_id) {

        return Template::search(['name'])
                        ->where('user_id', $user_id)
                        ->where('default', StatusEnum::FALSE->status())
                        ->where('plugin', StatusEnum::FALSE->status())
                        ->where('global', StatusEnum::FALSE->status())
                        ->routefilter()
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }
    public function getAdminTemplate() {

        return Template::search(['name'])
                        ->whereNull('user_id')
                        ->where('default', StatusEnum::FALSE->status())
                        ->where('plugin', StatusEnum::FALSE->status())
                        ->where('global', StatusEnum::FALSE->status())
                        ->routefilter()
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }

    public function getParentTemplate($id, $user_id = null) {

        return Template::search(['name'])
                        ->where('user_id', $user_id)
                        ->where('cloud_id', $id)
                        ->routefilter()
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }

    public function getGlobalTemplate() {

        return Template::where('global', StatusEnum::TRUE->status())->first();
    }

    public function getDefaultTemplate() {

        return Template::search(['name'])
                        ->whereNull('user_id')
                        ->where('default', StatusEnum::TRUE->status())
                        ->where('plugin', StatusEnum::FALSE->status())
                        ->where('global', StatusEnum::FALSE->status())  
                        ->routefilter()
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }

    public function getPluginTemplates() {

        return Template::search(['name'])
                        ->whereNull('user_id')
                        ->where('plugin', StatusEnum::TRUE->status())
                        ->routefilter()
                        ->filter(['status'])
                        ->latest()
                        ->date()
                        ->paginate(paginateNumber(site_settings("paginate_number")))->onEachSide(1)
                        ->appends(request()->all());
    }

    public function prepSmsData(int $type, $template, int $user_id = null) {

        return [
            'user_id'       => $user_id,
            'type'          => $type,
            'name' 	        => $template["name"],
            'plugin'        => StatusEnum::FALSE->status(),
            'default'       => StatusEnum::FALSE->status(),
            'global'        => StatusEnum::FALSE->status(),
            'slug'          => strtoupper(textFormat([' '], $template["name"], '_')), 
            'template_data' => $template["template_data"]
        ];
    }

    public function prepPredefinedEmailData($template) {

        return [
            'template_data' => $template["template_data"],
        ];
    }

    public function prepEmailData($template, $user_id) {

        $template_data = $template["template_data"];
        $template_json = $template["template_json"];
        $template_html = $template["template_html"];
        
        if(array_key_exists("template_json", $template) && $template['template_json'] != null && array_key_exists("template_html", $template) && $template['template_html'] != null) {
            $template_data = [

                "mail_body"     => $template_html,
                "template_json" => $template_json,
            ];
        }
        
        unset($template['template_data']);
        unset($template['template_json']);
        unset($template['template_html']);
        $data = $template;
        $data["template_data"] = $template_data;
        $data["user_id"]       = $user_id;
        $data["plugin"]        = StatusEnum::FALSE->status();
        $data["global"]        = StatusEnum::FALSE->status();
        $data["default"]       = StatusEnum::FALSE->status();
        $data["slug"]          = strtoupper(textFormat([' '], $template["name"], '_'));
        return $data;
    }

    public function prepTemplateData(int $type, $template, int $user_id = null) {

        $data = [];
        if($type == ServiceType::SMS->value) {

            $data = $this->prepSmsData($type, $template, $user_id);

        } elseif($type == ServiceType::WHATSAPP->value) {



        } elseif($type == ServiceType::EMAIL->value && ((array_key_exists("default", $template) && (int)$template["default"] == StatusEnum::TRUE->status()) || (array_key_exists("global", $template) && (int)$template["global"] == StatusEnum::TRUE->status()))) {

            $data = $this->prepPredefinedEmailData($template);

        } else {

            $data = $this->prepEmailData($template, $user_id);
        }
        return $data;
    }
    
    public function saveWhatsappTemplates(array $data, $request, int $user_id = null) {

        foreach ($data["data"] as $template) {
                
            $data = [

                'user_id'       => $user_id,
                'type'          => ServiceType::WHATSAPP->value,
                'cloud_id'      => $request->input("itemId"),
                'name' 	        => $template["name"],
                'plugin'        => StatusEnum::FALSE->status(),
                'global'        => StatusEnum::FALSE->status(),
                'default'       => StatusEnum::FALSE->status(),
                'slug'          => strtoupper(textFormat([' '], $template["name"], '_')), 
                'template_data' => $template,
                'status'        => $template["status"] == 'APPROVED' ? StatusEnum::TRUE->status() : StatusEnum::FALSE->status()
            ];
            
            $this->save($data, $request);
        }

        return $data;
    }

    public function whatsappCloudApiData($request) {

        $itemId = $request->input("itemId");
        Template::where('cloud_id', $itemId)->delete();

        $whatsapp_business_account = WhatsappDevice::find($itemId);
        $credentials 			   = $whatsapp_business_account->credentials;
        $token 					   = $credentials['user_access_token'];
        $waba_id 				   = $credentials['whatsapp_business_account_id'];
        $url 					   = "https://graph.facebook.com/v19.0/$waba_id/message_templates";
        $queryParams = [
            'fields' => 'name,category,language,quality_score,components,status',
            'limit'  => 100
        ];

        $headers = [
            'Authorization' => "Bearer $token"
        ];

        $response 	  = Http::withHeaders($headers)->get($url, $queryParams);
        $responseData = $response->json();

        return $responseData;

    }

    public function save($data, $request, $user_id = null) {

        if($user_id) {

            $data["user_id"] = $user_id;
        }

        $template = Template::updateOrCreate([

            'id' => $request->input("id"),
            
        ], $data);

        return $template;
    }

    public function statusUpdate($request) {
        
        try {
            $status   = true;
            $reload   = false;
            $message  = translate('Template status updated successfully');
            $template = Template::where("id",$request->input('id'))->first();
            $column   = $request->input("column");
            
            if($request->value == StatusEnum::TRUE->status()) {
                
                $template->status = StatusEnum::FALSE->status();
                $template->update();
            } else {

                $template->status = StatusEnum::TRUE->status();
                $template->update();
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

    
}
