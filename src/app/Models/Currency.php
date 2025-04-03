<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Filterable;

class Currency extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $guarded = [];
    
    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::TRUE->status());
    }

    public function scopeInactive($query)
    {
        return $query->where('status', StatusEnum::FALSE->status());
    }

    public function scopeRoutefilter(Builder $q) :Builder{

        return $q->when(request()->routeIs('admin.system.currency.inactive'),function($query) {
            
            return $query->inactive();
        })->when(request()->routeIs('admin.system.currency.active'),function($query) {
            
            return $query->active();
        });
    }
}
