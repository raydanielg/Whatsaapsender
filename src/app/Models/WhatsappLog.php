<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WhatsappLog extends Model
{
    use HasFactory;

    const PENDING 	 = 1;
	const SCHEDULE 	 = 2;
	const FAILED 	 = 3;
	const SUCCESS 	 = 4;
	const PROCESSING = 5;

	const NODE 		= 0;
	const CLOUD_API = 1;
	
	protected $guarded = [];

    protected $casts = [
        'file_info' => 'json',
    ];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function whatsappGateway()
	{
		return $this->belongsTo(WhatsappDevice::class, 'whatsapp_id');
	}

    protected static function booted()
    {
        static::creating(function ($whatsappLog) {
            $whatsappLog->uid = str_unique();
        });
    }
}
