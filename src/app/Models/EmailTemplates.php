<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'codes' => 'object'
    ];

    /**
     * get user name 
     */

     public function user(){
        return $this->belongsTo(User::class,'user_id');
     }
}
