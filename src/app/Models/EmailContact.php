<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_group_id',
        'email',
        'name',
        'status'
    ];

    public function emailGroup()
    {
    	return $this->belongsTo(EmailGroup::class, 'email_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     *scope filter
     */

     public function scopefilter($q,$request){
        
        return $q->when($request->status &&  $request->status !='All', function($q) use($request) {

            return $q->where('status', $request->status);
            })->when($request->search !=null,function ($q) use ($request) {
              
            return $q->where('name', 'like', '%' .$request->search.'%')
            ->orWhere('email', 'like', '%' .$request->search.'%');
        });
    }
}
