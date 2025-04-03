<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Filterable;

class Language extends Model
{
    use HasFactory, Filterable;

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function (Model $model) {
            $model->uid        = Str::uuid();
            $model->status     = StatusEnum::TRUE->status();
        });
    }
    public function scopeDefault(Builder $q) : Builder{
        return $q->where('is_default',(StatusEnum::TRUE)->status());
    }
    public function scopeActive(Builder $q) :Builder{
        return $q->where('status',(StatusEnum::TRUE)->status());
    }
}
