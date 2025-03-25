<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable =[
        'name',
        'user_id',
        'total',
        'discount',
        'vat',
        'payable'
   ];
   public function user()
   {
       return $this->belongsTo(User::class);
   }

   public function customer()
   {
       return $this->belongsTo(Customer::class);
   }

   public function invoiceProducts()
   {
       return $this->hasMany(InvoiceProduct::class);
   }
    
}
