<?php

namespace App\Models;

use App\Enums\Common\Status;
use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;

class PricingPlan extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'type',
        'description',
        'amount',
        'sms', 
        'email', 
        'whatsapp', 
        'duration',
        'status',
        'carry_forward',
        'recommended_status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'sms' => 'object',
        'email' => 'object',
        'whatsapp' => 'object',
    ];

    /**
     * columnExists
     *
     * @param mixed $columnName
     * 
     * @return bool
     */
    public static function columnExists($columnName): bool
    {
        $table = (new static)->getTable();
        $columnExists = Schema::hasColumn($table, $columnName);

        return $columnExists;
    }

    /**
     * booted
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::creating(function ($plan) {
            
            $plan->status = StatusEnum::TRUE->status();
        });
    }
    
    
    /**
     * scopeActive
     *
     * @return Builder
     */
    public function scopeActive(): Builder|PricingPlan {
        return $this->where(function(Builder $q): Builder {
            return $q->where('status', StatusEnum::TRUE->status())
                        ->orWhere("status", Status::ACTIVE->value);
        });
    }
}
