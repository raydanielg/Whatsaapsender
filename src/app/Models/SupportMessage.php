<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function supportfiles()
    {
        return $this->hasMany(SupportFile::class, 'support_message_id');
    }
}
