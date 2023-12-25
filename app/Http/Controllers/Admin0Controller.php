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
use App\Models\ProductOrder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\search1;
use App\Models\Order;

class Admin0Controller extends Controller

{
    use HttpResponses;

    public function Register(StoreadminRequest $request)
    {
        $request->validated($request->all);
        $user = admin::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of' . $user->name)->plainTextToken,
        ]);
    }

    public function Login(UpdateadminRequest $request)
    {
        $users = admin::where('phone', $request->phone)->first();
        if (!$users || !Hash::check($request->password, $users->password)) {
            return $this->error('', 'Credentials do not match', 401);
        }
        // $user=DB::table('users')->where($request->phone)->get();
        //   $user= DB::select('select * from users where phone = ?', $request->phone);

        // return $users;
        return $this->success([
            'user' => $users,
            'token' => $users->createToken('API Token of' . $users->name)->plainTextToken,

        ]);
    }

    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'you have successfully been logged out',
        ]);
    }


    public function InsertProduct(InsertIntoProduct $request)
    {

        if ($product = Product::create($request->validated())) {
            return response([
                'data' => $product,
                'message' => 'you  insert product successfully',
            ]);
        }
        return response([

            'message' => 'somthing wrong'
        ], 500);
    }
    public function InsertCategories(InsertIntoCategories $request)
    {

        if ($product = Categorie::create($request->validated())) {
            return response([
                'data' => $product,
                'message' => 'you  insert Categories successfully',
            ]);
        }
        return response([
            'message' => 'somthing wrong'
        ], 500);
    }
    public function getProduct()
    {
        $product = Product::get();
        return $product;
    }
    public function getCategories()
    {
        $product = Categorie::get();
        return $product;
    }
    public function getOrderDetails(User $user, Order $order)
    {

        $user = auth()->user();

        if ($orderDetails = $order->with('products')->get()) {
            return response([
                'data' => $orderDetails,
                'message' => 'this is your order',
            ]);
        }
        return response([
            'message' => 'this order does not exist'
        ], 500);
    }
    public function stauts(Request $request)
    {
        $id = $request->id;
        $order = $request->id_order;
        $array = ProductOrder::all();

        if ($id == 1) {
            DB::table('orders')->where('id', $order)->update(['status' => 'Has_Been_Sent']);

            foreach ($array as $item) {
                $product = Product::where('id', $item['product_id'])->first();

                if ($product) {
                    $quantityToSubtract = $item['quantity'];
                    $product->decrement('quantity_available', $quantityToSubtract);
                }
            }
            return response([
                'message' => 'the order status has been changed to Has_Been_Sent'
            ], 200);
        } else if ($id == 2) {
            DB::table('orders')->where('id', $order)->update(['status' => 'Received']);
            return response([
                'message' => 'the order status has been changed to Received'
            ], 200);
        }
    }


    public function paid(Request $request)
    {
        $id = $request->id;
        $order = $request->id_order;

        if ($id == 1) {
            DB::table('orders')->where('id', $order)->update(['payment_status' => 'paid']);
            return response([
                'message' => 'the order payment_status has been changed to paid'
            ], 200);
        }
    }
}
