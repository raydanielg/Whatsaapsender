<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Filterable;

class PaymentLog extends Model
{
    use HasFactory, Notifiable, Filterable;

    const PENDING = 1;
    const SUCCESS = 2;
    const CANCEL = 3;

    protected $fillable = [
    	'subscriptions_id',
    	'user_id',
    	'method_id',
    	'amount',
        'charge',
        'rate',
    	'final_amount',
    	'trx_number',
        'user_data',
    	'status',
    ];


    protected $casts = [
        'user_data' => 'object'
    ];

    public function paymentGateway()
    {
    	return $this->belongsTo(PaymentMethod::class,'method_id');
    }

    public function plan()
    {
        return $this->belongsTo(Subscription::class, 'subscriptions_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::SUCCESS);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::PENDING);
    }

    public function scopeCancel($query)
    {
        return $query->where('status', self::CANCEL);
    }

    
}
