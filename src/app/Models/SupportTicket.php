<?php

namespace App\Models;

use App\Enums\PriorityStatusEnum;
use App\Enums\TicketStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use App\Traits\Filterable;


class SupportTicket extends Model
{
    use HasFactory,  Notifiable, Filterable;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class, 'support_ticket_id');
    }

    public function scopeRunning($query)
    {
        return $query->where('status', TicketStatusEnum::RUNNING->value);
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', TicketStatusEnum::ANSWERED->value);
    }

    public function scopeReplied($query)
    {
        
        return $query->where('status', TicketStatusEnum::REPLIED->value);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', TicketStatusEnum::CLOSED->value);
    }
    public function scopePriorityHigh($query)
    {
        return $query->where('priority', PriorityStatusEnum::HIGH->value);
    }
    public function scopePriorityMedium($query)
    {
        
        return $query->where('priority', PriorityStatusEnum::MEDIUM->value);
    }
    public function scopePriorityLow($query)
    {   
        
        return $query->where('priority', PriorityStatusEnum::LOW->value);
    }

    public function scopeRoutefilter(Builder $q) :Builder{

        return $q->when(request()->routeIs('*.support.ticket.replied'),function($query) {
           
            return $query->replied();
        })->when(request()->routeIs('*.support.ticket.closed'),function($query) {
            
            return $query->closed();
        })->when(request()->routeIs('*.support.ticket.running'),function($query) {
            
            return $query->running();
        })->when(request()->routeIs('*.support.ticket.answered'),function($query) {
            
            return $query->answered();
        })->when(request()->routeIs('*.support.ticket.priority.high'),function($query) {
            
            return $query->priorityHigh();
        })->when(request()->routeIs('*.support.ticket.priority.medium'),function($query) {
            
            return $query->priorityMedium();
        })->when(request()->routeIs('*.support.ticket.priority.low'),function($query) {
            
            return $query->priorityLow();
        });
    }

}
