<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Filterable;

class Translation extends Model
{
    use HasFactory, Filterable;
    protected $guarded = [];
    protected static function booted()
    {
        static::creating(function (Model $model) {
            $model->uid        = Str::uuid();

        });
    }
}
