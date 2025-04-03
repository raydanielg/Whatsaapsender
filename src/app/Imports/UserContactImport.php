<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;

class UserContactImport implements ToCollection
{
   /**
     * @param array $row
     *
     * @return User|null
     */
    public function collection(array $rows){}
    
}
