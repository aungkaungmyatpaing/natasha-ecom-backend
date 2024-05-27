<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id','product_id','price','quantity','total_price','main_currency_unit','usd_total_price','baht_total_price','mmk_total_price','usd_price','baht_price','mmk_price'
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function order() {
        return $this->belongsTo(Order::class,'order_id','id');
    }
}