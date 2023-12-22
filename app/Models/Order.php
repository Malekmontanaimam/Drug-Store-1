<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
<<<<<<< HEAD
    protected $fillable = [
        'user_id',
    ];
    public function products()
{
    return $this->belongsToMany(Product::class)->withPivot('quantity');
}

=======
    use HasFactory;
    protected $fillable=[
    'user_id',
    ];
    
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
  
>>>>>>> b5aa45f6c0370f681e437858ac872f224b6a7c2c
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
