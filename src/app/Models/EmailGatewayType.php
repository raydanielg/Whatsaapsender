<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailGatewayType extends Model
{
    use HasFactory;

    protected $table = "email_gateway_types";

    protected $casts = [
        'gateway_credentials' => 'object',
    ];

      /**
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($gatewayType) {
            $gatewayType->uid = Str::uuid();
        });
    }

    public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
