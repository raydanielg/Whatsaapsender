<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AndroidApiSimInfo extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $fillable = [
        'android_gateway_id',
        'sim_number',
        'time_interval',
        'send_sms',
        'status'
    ];

    public function androidGateway()
    {
    	return $this->belongsTo(AndroidApi::class, 'android_gateway_id');
    }

     /**
     * @return HasMany
     */
    public function smsLog(): HasMany
    {
        return $this->hasMany(CommunicationLog::class, 'android_gateway_sim_id');
    }
}
