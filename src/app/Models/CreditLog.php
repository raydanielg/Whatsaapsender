<?php

namespace App\Models;

use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use App\Traits\Filterable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreditLog extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $guarded = [];

    protected static function booted() {

        static::creating(function (Model $model) {

            $model->uid = Str::uuid();
        });
    }
    
    public function scopeSms($query)
    {
        return $query->where('type', ServiceType::SMS->value);
    }

    public function scopeEmail($query)
    {
        return $query->where('type', ServiceType::EMAIL->value);
    }

    public function scopeWhatsapp($query)
    {
        return $query->where('type', ServiceType::WHATSAPP->value);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function scopeRoutefilter(Builder $q) :Builder{

        return $q->when(request()->routeIs('*.report.credit.sms'),function($query) {
            
            return $query->sms();
        })->when(request()->routeIs('*.report.credit.email'),function($query) {
            
            return $query->email();
        })->when(request()->routeIs('*.report.credit.whatsapp'),function($query) {
            
            return $query->whatsapp();
        });
    }
}
