<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable =[
        'name',
        'user_id',
        'email',
        'mobile'
   ];
    public function user(){
      return $this->belongsTo(User::class);
    }
    public function invoices(){
      return $this->hasMany(Invoice::class);
    }
    
}
