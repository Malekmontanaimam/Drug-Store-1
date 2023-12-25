<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\search1;
use App\Models\Categorie;
use App\Models\Product;
use App\Models\User;
use App\Traits\HttpResponses;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\Order;
use App\Http\Resources\OrderResource;

use App\Models\ProductOrder;


use League\Config\Exception\ValidationException as ExceptionValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Illuminate\Validation\ValidationException;

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
    public function search(search1 $request){
        $request->validated($request->all);
        $search=Categorie::where('name','like','%'.$request->name.'%')->get();
        if(!($search->isEmpty())){
            return response([
                'data'=>$search,
                'messag'=>'you search in categorie'
            ]);
        }
     else if($search->isEmpty()) {
        
        $search=Product::where('commercial_name','like','%'.$request->name.'%')->get(); 
         }
    if(!($search->isEmpty())){
                return response([
                    'data'=>$search,
                    'messag'=>'you search in commercial_name'
                ]);
        }
       else if($search->isEmpty()) 
       {
    
            return response([
                'messag'=>'not found'
            ]);
       }
    }
    public function Logout(Request $request){
    $request->user()->currentAccessToken()->delete();
    return $this->success([
        'message'=>'you have successfully been logged out',
        'data'=>$request->user()
    ],200);
    }
   
    

    public function show(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);
        $name=Product::where('category_id',$request->name)->get('scientific_name');
        return $name;
    }

    public function forgetpassword(Request $request){
        $request->validate([
            'email'=>'required|email',
            
        ]);

        $status=Password::sendResetLink(
            $request->only('email')
        );
     if($status==Password::RESET_LINK_SENT){
        return[
            'status'=>__($status)
        ];
     }
     throw ValidationException::withMessages([
        'email'=>[trans($status)],
     ]);
   
    }
    public function resetpassword(Request $request){
        $request->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>['required','confirmed',RulesPassword::defaults()],
        ]);
        $status=Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user) use($request){
                $user->forceFill([
                    'password'=>Hash::make($request->password),
                    'remember_token'=>Str::random(60),
                ])->save();
            event(new PasswordReset($user));
            }
        );
        if($status==Password::PASSWORD_RESET){
            return response([
                'message'=>'Password reset successfully',
            ]);
        }
        return response([
            'message'=>__($status)
        ],500);

    }
   
public function getOrderDetailsid()
{
    $user = auth()->user();

    
    $orderDetails = Order::where('user_id', $user->id)->with('products')->get();

    if($orderDetails){
        return response([
            'data'=>$orderDetails ,
            'message'=>'this is your order',
        ]);
    }

    return response([
        'message'=>'you do not have any orders yet'
    ],500);
}
}
    
