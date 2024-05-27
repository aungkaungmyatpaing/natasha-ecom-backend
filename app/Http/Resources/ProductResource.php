<?php

namespace App\Http\Resources;

use App\Models\ProductImage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $image = ProductImage::where('product_id',$this->id)->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
