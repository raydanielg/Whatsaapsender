<?php

namespace App\Models;

use App\Enums\CampaignStatusEnum;
use App\Enums\CommunicationStatusEnum;
use App\Enums\ServiceType;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

class Campaign extends Model
{
    use HasFactory, Notifiable, Filterable;
    public $timestamps = true;
    protected $guarded = [];

    protected $casts = [

        'meta_data' => 'array'
    ];

    protected static function booted() {

        static::creating(function (Model $model) {
            
            $model->uid = Str::uuid();
            $model->status = CampaignStatusEnum::ACTIVE->value;
            $model->created_at = $model->freshTimestamp();
            $model->updated_at = null;
        });
    }

    public function scopeSms($query) {
        
        return $query->where('type', ServiceType::SMS->value);
    }
    public function scopeWhatsapp($query) {

        return $query->where('type', ServiceType::WHATSAPP->value);
    }
    public function scopeEmail($query) {

        return $query->where('type', ServiceType::EMAIL->value);
    }

    public function scopeRoutefilter(Builder $q) :Builder {

        return $q->when(request()->routeIs('*.communication.sms.campaign.index'),function($query) {
            
            return $query->sms();
        })->when(request()->routeIs('*.communication.whatsapp.campaign.index'),function($query) {
            
            return $query->whatsapp();
        })->when(request()->routeIs('*.communication.email.campaign.index'),function($query) {
            
            return $query->email();
        });
    }

    public function communicationLog() {

        return $this->hasMany(CommunicationLog::class, 'campaign_id', 'id');
    }

    public function communicationPendingLog() {

        return $this->hasMany(CommunicationLog::class, 'campaign_id', 'id')->where('status', CommunicationStatusEnum::PENDING->value);
    }

    public function getRelationships() {

        return ['communicationPendingLog'];
    }
    public function user() {

        return $this->belongsTo(User::class, 'user_id');
    }

    public function setUpdatedAt($value)
    {
        if ($this->exists) {
            $this->updated_at = $value;
        }
    }

    public function unsubscribes()
    {
        return $this->hasMany(CampaignUnsubscribe::class);
    }

    /**
     *
     * @param  string  $contactUid
     * @param  int     $channel
     * @return bool
     */
    public function hasContactUnsubscribed($contactUid, $channel)
    {
        return $this->unsubscribes()
                    ->where('contact_uid', $contactUid)
                    ->where('channel', $channel)
                    ->exists();
    }
}
