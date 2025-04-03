<?php

namespace App\Imports;

use App\Models\EmailContact;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class EmailContactImport implements ToCollection, WithHeadingRow
{
    protected $groupId;
    protected $status;

    public function __construct($groupId, $status) {
        $this->groupId = $groupId;
        $this->status = $status;
    }

    public function collection(Collection $rows)
    {

        $data = [];
        foreach ($rows as $val){
            foreach($val as $key=>$dataVal){
                if($dataVal !=''){
                    if(filter_var($dataVal, FILTER_VALIDATE_EMAIL)){
                        $data['email'] = $dataVal;
                    }
                    else{
                        $data['name'] = $dataVal;
                    }
                }
            }
            EmailContact::create([
                'user_id' => $this->status == true ? null : auth()->user()->id,
                'email_group_id' => $this->groupId,
                'name'=> $data['name'],
                'email'=>$data['email'],
                'status'=> 1,
            ]);
        }

    }
}
