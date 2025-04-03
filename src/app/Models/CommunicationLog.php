<?php

namespace App\Models;

use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Traits\Filterable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CommunicationLog extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $guarded = [];

    protected $casts = [

        'meta_data' => 'json',
        'message'   => 'json',
        'file_info' => 'json',
    ];
    protected static function booted() {

        static::creating(function (Model $model) {
            
            $model->uid = substr(Str::uuid(), 0, 32);
        });
    }

    public function scopeSms($query) {

        return $query->where('type', ServiceType::SMS->value);
    }
    public function scopeWhatsapp($query) {

        return $query->where('type', ServiceType::WHATSAPP->value);
    }
    public function scopeEmail($query) {

        return $query->where('type', ServiceType::EMAIL->value);
    }

    public function scopeRoutefilter(Builder $q) :Builder {

        return $q->when(request()->routeIs('*.communication.sms.index'),function($query) {
            
            return $query->sms();
        })->when(request()->routeIs('*.communication.whatsapp.index'),function($query) {
            
            return $query->whatsapp();
        })->when(request()->routeIs('*.communication.email.index'),function($query) {
            
            return $query->email();
        });
    }

    public function user() {

        return $this->belongsTo(User::class, 'user_id');
    }

    public function simInfo()
    {
    	return $this->belongsTo(AndroidApiSimInfo::class, 'android_gateway_sim_id');
    }

    public function whatsappGateway()
	{
		return $this->belongsTo(WhatsappDevice::class, 'gateway_id');
	}

    public function sender()
    {
    	return $this->belongsTo(Gateway::class, 'gateway_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
