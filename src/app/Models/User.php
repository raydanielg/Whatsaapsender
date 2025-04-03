<?php

namespace App\Models;

use App\Enums\Common\Status;
use App\Enums\StatusEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gateway_credentials' => 'object',
        'address'              => 'object',  
    ];

    protected static function booted()
    {
        static::creating(function ($contact) {
            $contact->uid = str_unique();
        });
    }
    public function scopeVerified($query)
    {
        return $query->where('email_verified_status', StatusEnum::TRUE->status());
    }
    public function scopeUnverified($query)
    {
        return $query->where('email_verified_status', StatusEnum::FALSE->status());
    }

     /**
     * scopeActive
     *
     * @return Builder
     */
    public function scopeActive(): Builder|User {
        return $this->where(function(Builder $q): Builder {
            return $q->where('status', StatusEnum::TRUE->status())
                        ->orWhere("status", Status::ACTIVE->value);
        });
    }

    public function scopeBanned($query)
    {
        return $query->where('status', StatusEnum::FALSE->status());
    }

    public function ticket()
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    public function group()
    {
        return $this->hasMany(ContactGroup::class, 'user_id');
    }

    public function emailGroup()
    {
        return $this->hasMany(ContactGroup::class, 'user_id');
    }

    public function contact()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    public function emailContact()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }


    public function template()
    {
        return $this->hasMany(Template::class, 'user_id')->latest();
    }

    public function gateway()
    {
        return $this->hasMany(Gateway::class, 'user_id');
    }

    public function runningSubscription() {

        return $this->hasMany(Subscription::class, 'user_id')->where('status', Subscription::RUNNING)->orWhere('status', Subscription::RENEWED)->first();
    }

    public function scopeRoutefilter(Builder $q) :Builder{

        return $q->when(request()->routeIs('admin.user.banned'),function($query) {

            return $query->banned();
        })->when(request()->routeIs('admin.user.active'),function($query) {
            
            return $query->active();
        });
    }
}
