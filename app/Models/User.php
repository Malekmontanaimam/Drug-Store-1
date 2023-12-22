<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
    ];
<<<<<<< HEAD
         
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
=======
    public function orders(){
        return $this->hasMany(Order::class);

    }

>>>>>>> b5aa45f6c0370f681e437858ac872f224b6a7c2c
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
  
     function sendPasswordResetNotification($token)
    {
        $url='https://spa.test/reset-password?token=' . $token;
        $this->notify(new ResetPasswordNotification($url));  
    }

}