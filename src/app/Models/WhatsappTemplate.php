<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'cloud_id',
        'user_id',
        'language_code',
        'name',
        'category',
        'template_information',
        'status'
    ];

    protected $casts = [
        "template_information" => "json"
    ];

    protected static function booted() {
        static::creating(function ($contact) {
            $contact->uid = str_unique();
        });
    }

    public function businessApi()
    {
    	return $this->belongsTo(WhatsappDevice::class, 'cloud_id');
    }
  
}
