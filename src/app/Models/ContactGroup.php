<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\Filterable;
use App\Enums\Common\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactGroup extends Model
{
    use HasFactory, Notifiable, Filterable;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($group) {

            $group->uid    = str_unique();
            $group->status = Status::ACTIVE->value;
        });
    }

    /**
     * contacts
     *
     * @return HasMany
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'group_id', 'id');
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
        return $query->where('status', StatusEnum::TRUE->status());
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
        return $query->where('status', StatusEnum::FALSE->status());
    }

    /**
     * scopeAdmin
     *
     * @param mixed $query
     * 
     * @return Builder
     */
    public function scopeAdmin($query): Builder
    {
        return $query->whereNull('user_id');
    }

    /**
     * getRelationships
     *
     * @return array
     */
    public function getRelationships(): array
    {
        return ['contacts'];
    }
}
