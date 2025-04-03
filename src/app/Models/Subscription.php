<?php

namespace App\Models;

use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Filterable;

class Subscription extends Model
{
    use HasFactory, Notifiable, Filterable;

    
    const RUNNING   = 1;
    const EXPIRED   = 2;
    const REQUESTED = 3;
    const INACTIVE  = 4;
    const RENEWED   = 5;
    

    protected $fillable = [
    	'user_id',
    	'plan_id',
    	'expired_date',
    	'trx_number',
    	'amount',
    	'status'
    ];

    protected $dates = ['created_at', 'updated_at', 'expired_date'];

    public function plan()
    {
    	return $this->belongsTo(PricingPlan::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currentPlan()
    {
    	return $this->hasMany(PricingPlan::class, 'id', 'plan_id')->first();
    }
}

