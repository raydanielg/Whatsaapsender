<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AndroidApi extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable, Filterable;

    protected $fillable = [
        'name',
        'password',
        'show_password',
        'admin_id',
        'user_id',
        'status',
    ];

    /**
     * @return HasMany
     */
    public function simInfo(): HasMany
    {
        return $this->hasMany(AndroidApiSimInfo::class, 'android_gateway_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', StatusEnum::TRUE->status());
    }

    public function scopeBanned($query)
    {
        return $query->where('status', StatusEnum::FALSE->status());
    }


    public function scopeRoutefilter(Builder $q) :Builder{

        return $q->when(request()->routeIs('admin.user.banned'),function($query) {

            return $query->banned();
        })->when(request()->routeIs('admin.user.active'),function($query) {
            
            return $query->active();
        });
    }
    public function getRelationships()
    {
        return ['simInfo'];
    }
}
