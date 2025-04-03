<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;
    const DATE    = 1;
    const BOOLEAN = 2;
    const NUMBER  = 3;
    const TEXT    = 4;
    
    protected $casts = [
    	'frontend_section'   => 'object',
    	'social_login'       => 'json',
    	'recaptcha'          => 'json',
    	'webhook'          => 'json',
    ];
}
