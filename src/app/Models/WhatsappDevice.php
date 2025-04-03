<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use App\Traits\Filterable;

class WhatsappDevice extends Model
{
    use HasFactory, Notifiable, Filterable;
    protected $table = 'wa_device';
    protected $guarded = []; 

    const DISCONNECTED = "disconnected";
    const INITIATED    = "initiated";
    const CONNECTED    = "connected";

    protected $casts = [

        "credentials" => "json"
    ];

    protected static function booted()
    {
        static::creating(function ($contact) {
            $contact->uid = str_unique();
        });
    }

    public function template()
    {
        return $this->hasMany(Template::class, 'cloud_id');
        
    }

    public function scopeDevice($query)
    {
        return $query->where('type', StatusEnum::FALSE->status());
    }

    public function scopeCloudApi($query)
    {
        return $query->where('type', StatusEnum::TRUE->status());
    }


    public function scopeRoutefilter(Builder $q) :Builder{

        return $q->when(request()->routeIs('*.gateway.whatsapp.device'),function($query) {

            return $query->device();
        })->when(request()->routeIs('*.gateway.whatsapp.cloudApi'),function($query) {
            
            return $query->cloudApi();
        });
    }
}
