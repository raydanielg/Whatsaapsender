<?php

namespace App\Models;

use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $guarded = [];

    protected $casts = [

        "template_data" => "array"
    ];

    protected static function booted() {
        static::creating(function ($template) {
            $template->uid = str_unique();
        });
    }

    
    
    public function user() {

    	return $this->belongsTo(User::class, 'user_id');
    }

    public function cloudApi() {
        
    	return $this->belongsTo(WhatsappDevice::class, 'cloud_id');
    }

    public function scopeSms($query)
    {
        return $query->where('type', ServiceType::SMS->value);
    }
    public function scopeWhatsapp($query)
    {
        return $query->where('type', ServiceType::WHATSAPP->value);
    }
    public function scopeEmail($query)
    {
        return $query->where('type', ServiceType::EMAIL->value);
    }

    public function scopeRoutefilter(Builder $q) :Builder{

        return $q->when(request()->routeIs('*.template.sms'),function($query) {

            return $query->sms();
        })->when(request()->routeIs('*.template.whatsapp.index'),function($query) {
            
            return $query->whatsapp();
        })->when(request()->routeIs('*.template.email'),function($query) {
            
            return $query->email();
        });
    }
}
