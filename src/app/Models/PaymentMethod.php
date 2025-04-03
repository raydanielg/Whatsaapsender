<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory, Filterable;

    protected $guarded = [];

    protected $casts = [

        'payment_parameter' => 'object'
    ];

    public function scopeManualMethod()
    {
        return $this->where('unique_code','LIKE','%MANUAL%');
    }

    public function scopeAutomaticMethod()
    {
        return $this->where('unique_code','NOT LIKE','%MANUAL%');
    }

    public function scopeRoutefilter(Builder $q) :Builder{

        return $q->when(request()->routeIs('admin.payment.automatic.index'),function($query) {

            return $query->automaticMethod();
        })->when(request()->routeIs('admin.payment.manual.index'),function($query) {
            
            return $query->manualMethod();
        });
    }
}
