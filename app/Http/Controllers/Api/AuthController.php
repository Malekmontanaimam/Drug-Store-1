<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

use App\Models\User;
use App\Traits\HttpResponses;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\search1;
use App\Models\Product;
use App\Models\Categorie;


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
        if(!$search->isEmpty()){
            return response([
                'data'=>$search,
                'messag'=>'you search in categorie'
            ]);
        }
     else {
                $search=Product::where('Commercial_name','like','%'.$request->name.'%')->get();
         }
    if(!$search->isEmpty()){
                return response([
                    'data'=>$search,
                    'messag'=>'you search in product'
                ]);
        }
       else
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



    public function makeOrder(User $user, Request $request)
{

    $validatedData = $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'products' => 'required|array',
        'products.*.id' => 'required|integer|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
    ]);


    $user = auth()->user();

    $order = Order::create([
        'user_id' => $validatedData['user_id'],
    ]);



    foreach ($validatedData['products'] as $productData) {
        $order->products()->attach($productData['id'], ['quantity' => $productData['quantity']]);
    }


    return new OrderResource($order);
}

}
