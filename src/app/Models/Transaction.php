<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Filterable;

class Transaction extends Model
{
    use HasFactory, Notifiable, Filterable;
    const PLUS = '+';
    const MINUS = "-"; 

    protected $fillable = [
        'seller_id', 'user_id', 'payment_method_id', 'amount', 'post_balance', 'transaction_type', 'transaction_number', 'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function paymentGateway()
    {
    	return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

}
