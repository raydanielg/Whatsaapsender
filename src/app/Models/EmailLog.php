<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailLog extends Model
{
    use HasFactory;

    const PENDING = 1;
	const SCHEDULE = 2;
	const FAILED = 3;
	const SUCCESS = 4;
    protected $guarded = [];


	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

    public function sender()
    {
    	return $this->belongsTo(Gateway::class, 'sender_id');
    }

    protected static function booted()
    {
        static::creating(function ($emailLog) {
            $emailLog->uid = str_unique();
        });
    }

}
