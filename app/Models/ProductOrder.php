<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_id',
        'quantity',
        'order_id'
        ];
        
        protected $table ='order';
        function product(){
            return $this->belongsTo(Product::class,'product_id','id');
            
        }
        function order(){
            return $this->belongsTo(Order::class,'order_id','id');
            
        }
}
