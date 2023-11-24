<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function Register(RegisterRequest $request){
        $request->validated($request->all);
        $user=User::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        return $this->success([
            'user'=>$user,
            'token'=>$user->createToken('API Token of' .$user->name)->plainTextToken,
        ]);
    }

    public function Login(LoginRequest $request){
       
        if(!Auth::attempt($request->validated())){
            return $this->error('','Credentials do not match',401);

        }  
       // $user=DB::table('users')->where($request->phone)->get();
    //   $user= DB::select('select * from users where phone = ?', $request->phone);
        $users=User::where('phone',$request->phone)->first();
      // return $users;
        return $this->success([
            'user'=>$users,
            'token'=>$users->createToken('API Token of' .$users->name)->plainTextToken,
  
        ]);

    }
    
    public function Logout(){
        
    }
}
