<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use App\Http\Resources\OrderResource;


class OrderController extends Controller
{
    public function addproduct(User $user ,Request $request,)
    {
        $validatedData=$request->validate([
                        'user_id' => 'required|integer|exists:users,id',
                        'products' => 'required|array',
                        'products.*.id' => 'required|integer|exists:products,id',
                        'products.*.quantity' => 'required|integer|min:1',
                    ]);
            
    $order=Order::create([
           'user_id' =>$validatedData['user_id'],
    ]);

   $user=auth()->user();
   foreach($validatedData['products'] as $productDats)
   {
    $order->products()->attach($productDats['id'],['quantity'=>$productDats['quantity']]);
   }
   return new OrderResource($order);
}
}