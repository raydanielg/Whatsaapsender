<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Blog extends Model
{
    use HasFactory, Filterable;
    protected $guarded = [];

    protected $casts = [

        'meta_data' => 'json',
        'file_info' => 'json',
    ];
    protected static function booted() {

        static::creating(function (Model $model) {
            
            $model->uid = substr(Str::uuid(), 0, 32);
            $model->status = StatusEnum::TRUE->status();
        });
    }

    public function scopeActive($query) {

        return $query->where('status', StatusEnum::TRUE->status());
    }
    public function scopeWhatsapp($query) {

        return $query->where('status', StatusEnum::FALSE->status());
    }
}
