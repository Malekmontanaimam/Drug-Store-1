<?php

namespace App\Http\Controllers;

use App\Models\admin;
use App\Http\Requests\StoreadminRequest;
use App\Http\Requests\UpdateadminRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;

class Admin0Controller extends Controller
    
{
        use HttpResponses;
    
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
        public function InsertProduct(InsertIntoProduct $request){
            $product=Product::create($request->validated());
        }
        public function InsertCategories(InsertIntoCategories $request){
            $product=Categories::create($request->validated());
        }
        public function getProduct(){
            $product=Product::get();
            return $product;
        }
        public function getCategories(){
            $product=Categories::get();
            return $product;
        }
    }
    



