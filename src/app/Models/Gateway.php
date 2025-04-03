<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Notifications\Notifiable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Gateway extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $table = "gateways";

    protected $guarded = [];
    
    protected $casts = [
        'mail_gateways' => 'object',
        'sms_gateways'  => 'object',
    ];

    protected static function booted()
    {
        static::creating(function ($smsLog) {
            $smsLog->uid = Str::uuid();
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::TRUE->status());
    }

    public function scopeInactive($query)
    {
        return $query->where('status', StatusEnum::FALSE->status());
    }

    public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

    public function scopeMail($query)
    {
        return $query->whereNotNull('mail_gateways');
    }
    public function scopeSms($query)
    {
        return $query->whereNotNull('sms_gateways');
    }
}
