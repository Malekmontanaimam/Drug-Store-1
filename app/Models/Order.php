<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable=[
    'user_order'
    ];
    
    protected $table ='order';
    function user(){
        return $this->belongsTo(User::class,'user_order','id');
    }
    function productorder(){
        return $this->belongsTo(ProductOrder::class,'order_id','id');
        
    }
}
