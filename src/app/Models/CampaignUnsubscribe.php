<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class CampaignUnsubscribe extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $guarded = [];

    protected $casts = [
        'meta_data' => 'object', 
    ];

    /**
     * Get the campaign associated with the unsubscribe entry.
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
    
    /**
     * Get the contact associated with the unsubscribe entry.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_uid', 'uid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
