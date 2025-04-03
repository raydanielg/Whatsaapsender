<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SMSlog extends Model
{
    use HasFactory;

    protected $guarded = [];

	const PENDING    = 1;
	const SCHEDULE 	 = 2;
	const FAILED     = 3;
	const SUCCESS 	 = 4;
	const PROCESSING = 5;


	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function smsGateway()
	{
		return $this->belongsTo(Gateway::class, 'api_gateway_id');
	}

	public function androidGateway()
	{
		return $this->belongsTo(AndroidApiSimInfo::class, 'android_gateway_sim_id');
	}


    /**
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($smsLog) {
            $smsLog->uid = str_unique();
        });
    }


}
