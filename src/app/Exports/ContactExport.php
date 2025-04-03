<?php

namespace App\Exports;

use App\Models\Contact;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ContactExport implements FromView, WithColumnWidths, WithStyles
{

    protected $status;
    protected $groupId;

    public function __construct($status, $groupId =null) {
        $this->status = $status;
        $this->groupId = $groupId;
    }

    public function view(): View
    {
        if($this->status && !$this->groupId){
            $contacts = Contact::whereNull('user_id')->select('name','contact_no')->get();
        }else if(!$this->status && $this->groupId){
            $contacts = Contact::where('group_id', $this->groupId)->select('name','contact_no')->get();
        }else{
            $contacts = Contact::where('user_id', auth()->user()->id)->select('name','contact_no')->get();
        }
        return view('partials.contact_excel', [
            'contacts' => $contacts,
        ]);
    }


    public function styles(Worksheet $sheet)
    {
    	return [
            'A1' => ['font' => ['bold' => true,'size' => 12,]]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30
        ];
    }



}
