<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Http\Requests\StoreadminRequest;
use App\Http\Requests\UpdateadminRequest;

class AdminController extends Controller
    
{
        use HttpResponses;
    
        public function Register(StoreadminRequest $request){
            $request->validated($request->all);
            $user=admin::create([
                'name'=>$request->name,
                'phone'=>$request->phone,
                'password'=>Hash::make($request->password),
            ]);
            return $this->success([
                'user'=>$user,
                'token'=>$user->createToken('API Token of' .$user->name)->plainTextToken,
            ]);
        }
    
        public function Login(UpdateadminRequest $request){
           
            if(!Auth::attempt($request->validated())){
                return $this->error('','Credentials do not match',401);
    
            }  
           // $user=DB::table('users')->where($request->phone)->get();
        //   $user= DB::select('select * from users where phone = ?', $request->phone);
            $users=admin::where('phone',$request->phone)->first();
          // return $users;
            return $this->success([
                'user'=>$users,
                'token'=>$users->createToken('API Token of' .$users->name)->plainTextToken,
      
            ]);
    
        }
        
        public function Logout(Request $request){
        $request->admin()->currentAccessToken()->delete();
        return $this->success([
            'message'=>'you have successfully been logged out',
        ]);
        }
    }
    



