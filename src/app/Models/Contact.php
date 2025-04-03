<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Enums\Common\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $fillable = [
        'uid',
        'user_id',
        'group_id',
        'meta_data',
        'whatsapp_contact',
        'email_contact',
        'sms_contact',
        'last_name',
        'first_name',
        'status',
        'email_verification'
    ];

    protected $casts = [
        "meta_data" => "object"
    ];

    protected static function booted()
    {
        static::creating(function ($contact) {
            
            $contact->uid    = str_unique();
            $contact->status = Status::ACTIVE->value;
        });
    }

    public function group(): BelongsTo
    {
    	return $this->belongsTo(ContactGroup::class, 'group_id', 'id');
    }

    /**
     * scopeAdmin
     *
     * @return Builder
     */
    public function scopeAdmin(): Builder
    {
        return $this->whereNull('user_id');
    }

    /**
     * user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * scopeActive
     *
     * @param mixed $query
     * 
     * @return Builder
     */
    public function scopeActive($query): Builder
    {
        return $query->where('status', Status::ACTIVE->value);
    }

    /**
     * scopeInactive
     *
     * @param mixed $query
     * 
     * @return Builder
     */
    public function scopeInactive($query): Builder 
    {
        return $query->where('status', Status::INACTIVE->value);
    }
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * unsubscribes
     *
     * @return HasMany
     */
    public function unsubscribes(): HasMany
    {
        return $this->hasMany(CampaignUnsubscribe::class, 'contact_uid', 'uid');
    }

    /**
     * @param  int  $campaignId
     * @param  int  $channel
     * @return bool
     */
    public function hasUnsubscribedFrom($campaignId, $channel): bool
    {
        return $this->unsubscribes()
                    ->where('campaign_id', $campaignId)
                    ->where('channel', $channel)
                    ->exists();
    }
}
