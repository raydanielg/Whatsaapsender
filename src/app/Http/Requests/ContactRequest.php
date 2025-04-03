<?php

namespace App\Http\Requests;

use App\Enums\StatusEnum;
use App\Models\Contact;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      
        $rules = [
            'group_id' => 'required|exists:contact_groups,id',
            "status"   => ["in,".StatusEnum::TRUE->status(), StatusEnum::FALSE->status()],
        ];

        
        if ($this->input('import_contact') == "true") {

            $rules += [

                'file' => ['required'],
            ];
            
        } else {
            
            $rules += [
                'first_name'      => 'required',
                'last_name'       => 'required',
                'whatsapp_number' => 'numeric',
                'sms_number'      => 'numeric',
                'email_account'   => 'email:rfc,dns',
            ];
        }
        if(request()->routeIs("admin.contact.update")) {

            $rules ['uid'] = "required|exists:contacts,uid";
        }
        return $rules;
    }
}
