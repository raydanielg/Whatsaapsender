<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Filterable;

class WhatsappCreditLog extends Model
{
    use HasFactory, Notifiable, Filterable;

    /**
     * @var string[]
     */
    protected  $fillable = [
        'user_id', 'type', 'credit', 'trx_number', 'post_credit', 'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
