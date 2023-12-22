<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'Products'=>$this->Products->map(function ($product)
            {
                return [
                    'id'=>$product->id,
                    'category_id'=>$product->category_id,
                    'product_name'=>$product->product_name,
                    'scientific_name'=>$product->scientific_name,
                    'commercial_name'=>$product->commercial_name,
                    'company'=>$product->company,
                    'quantity_available'=>$product->quantity_available,
                    'createdat'=>$product->createdat,
                    'cost'=>$product->cost,
                    'pivot'=>[
                        'quantity'=>$product->pivot->quantity,
                    ],
                    ];
            }),
        ];
    }
}
