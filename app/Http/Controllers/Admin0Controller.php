<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertIntoCategories;
use App\Http\Requests\InsertIntoProduct;
use App\Http\Requests\RegisterRequest;
use App\Models\admin;
use App\Http\Requests\StoreadminRequest;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateadminRequest;
use App\Models\Categorie;
use App\Models\Product;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\search1;










class Admin0Controller extends Controller
    
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
            $users=admin::where('phone',$request->phone)->first();
            if(!$users||!Hash::check($request->password,$users->password)){
                return $this->error('','Credentials do not match',401);
    
            }  
           // $user=DB::table('users')->where($request->phone)->get();
        //   $user= DB::select('select * from users where phone = ?', $request->phone);
            
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
        return $this->success([
            'user'=>$user,
            'token'=>$user->createToken('API Token of' .$user->name)->plainTextToken,
        ]);
    }


        public function InsertProduct(InsertIntoProduct $request){
          
            if(  $product=Product::create($request->validated())){
                return response([
                    'data'=>$product,
                    'message'=>'you  insert product successfully',
                ]);
            }
            return response([
                
                'message'=>'somthing wrong'
            ],500);
    

        }
        public function InsertCategories(InsertIntoCategories $request){
            
            if( $product=Categorie::create($request->validated())){
                return response([
                    'data'=>$product,
                    'message'=>'you  insert Categories successfully',
                ]);
            }
            return response([
                'message'=>'somthing wrong'
            ],500);
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
    



