<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailGroup extends Model
{
    use HasFactory;


     protected $fillable = [
        'user_id',
        'name',
        'status'
    ];


    public function user()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
    public function contact()
    {
        return $this->hasMany(EmailContact::class, 'email_group_id');
    }

     /**
     *scope filter
     */
    public function scopefilter($q,$request){
        
        return $q->when($request->status &&  $request->status !='All', function($q) use($request) {

            return $q->where('status', $request->status);
            })->when($request->search !=null,function ($q) use ($request) {
              
            return $q->where('name', 'like', '%' .$request->search.'%');
        });
    }

}
