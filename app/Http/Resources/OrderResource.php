<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_submitted_date' => $this->created_at->format('Y-m-d h:i:s'),
            'preorder_date' => $this?->preorder_date,
            'customer_id' => $this->customer_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'payment_method' => $this->payment_method,
            'payment_id' => $this->payment_id,
            'payment_photo' => $this->payment_photo,
            'status' => $this->status,
            'currency_unit' => $this->currency_unit,
            'user_selected_currency' => $this->user_selected_currency,
            'usd_grand_total' => $this->usd_grand_total,
            'baht_grand_total' => $this->baht_grand_total,
            'mmk_grand_total' => $this->mmk_grand_total,
            'order_items' => OrderItemResource::collection($this->orderItem),
        ];
    }
}
