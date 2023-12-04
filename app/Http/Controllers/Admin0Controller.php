<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreadminRequest;
use App\Http\Requests\UpdateadminRequest;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\search1;
use App\Models\Product;
use App\Models\Categorie;
use App\Http\Requests\InsertIntoCategories;
use App\Http\Requests\InsertIntoProduct;









class Admin0Controller extends Controller
    
{
    use HttpResponses;

    public function Register(StoreadminRequest $request){
        $request->validated($request->all);
        $user=Admin::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'password'=>Hash::make($request->password),
        ]);
        return $this->success([
            'user'=>$user,
            'token'=>$user->createToken('API Token of' .$user->name)->plainTextToken,
        ]);
    }

    // public function Login(Request $request){

    //     // $request->validate([
    //     //     'phone'=>'required',
    //     //     'password'=>'required'
    //     // ]);




    //     $users=Admin::where('phone',$request->phone)->first();
    //     $user=Admin::where('password',$request->password)->first();
    //     if(!$users||!$user){
    //         return $this->error('','Credentials do not match',401);

    //     } 
       
    //    // $user=DB::table('users')->where($request->phone)->get();
    // //   $user= DB::select('select * from users where phone = ?', $request->phone);
        
    //   // return $users;
    //     return $this->success([
    //         'user'=>$users,
    //         'token'=>$users->createToken('API Token of' .$users->name)->plainTextToken,
  
    //     ]);
        

    // }
    public function Login(LoginRequest $request){
       
        if(!Auth::attempt($request->validated())){
            return $this->error('','Credentials do not match',401);

        }  
       // $user=DB::table('users')->where($request->phone)->get();
    //   $user= DB::select('select * from users where phone = ?', $request->phone);
        $users=Admin::where('phone',$request->phone)->first();
      // return $users;
        return $this->success([
            'user'=>$users,
            'token'=>$users->createToken('API Token of' .$users->name)->plainTextToken,
  
        ]);

    }
    
    public function Logout(Request $request){
    $request->user()->currentAccessToken()->delete();
    return $this->success([
        'message'=>'you have successfully been logged out',
        'data'=>$request->user()
    ],200);
    }
        public function InsertProduct(InsertIntoProduct $request){
            $product=Product::create($request->validated());
            return $product;
        }
        public function InsertCategories(InsertIntoCategories $request){
            $product=Categorie::create($request->validated());
            return $product;
        }
        public function getProduct(){
            $product=Product::get();
            return $product;
        }
        public function getCategories(){
            $product=Categorie::get();
            return $product;
        }
    }
    



