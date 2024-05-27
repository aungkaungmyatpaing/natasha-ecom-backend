<?php

namespace App\Http\Resources;

use App\Models\ProductImage;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $image = ProductImage::where('product_id',$this->product->id)->first();

        $product = [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'photo' => $image->path
        ];
        
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product_info' => $product,
            'usd_price' => $this->usd_price,
            'baht_price' => $this->baht_price,
            'mmk_price' => $this->mmk_price,
            'main_currency_unit' => $this->main_currency_unit, 
            'quantity' => $this->quantity,
            'usd_total_price' => $this->usd_total_price, 
            'baht_total_price' => $this->baht_total_price, 
            'mmk_total_price' => $this->mmk_total_price
        ];
    }
}
