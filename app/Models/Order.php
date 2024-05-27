<?php

namespace App\Models;

use App\Casts\Image;
use DateTimeInterface;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\OrderCurrencyRate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'payment_photo' => Image::class,
    ];

    // protected $appends = [
    //     'currencies'
    // ];


    protected $fillable = [
        'customer_id','payment_id','payment_photo','payment_method','name','phone','address','grand_total','status','preorder_date','currency_rate','currency_unit','user_selected_currency'
    ];

    public function orderItem(){
        return $this->hasMany(OrderItem::class,'order_id','id');
    }

    public function product(){
        return $this->belongsTo(Payment::class,'product_id','id');
    }

    public function payment(){
        return $this->belongsTo(Payment::class,'payment_id','id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class,'customer_id','id');
    }

    public function orderCurrencyRate(){
        return $this->hasMany(OrderCurrencyRate::class,'order_id','id');
    }

    public function changeDateFormat(DateTimeInterface $date){
        return $date->format('d/m/Y h:i A');
    }

    // public function getCurrenciesAttribute(){
    //     $productPrices = $this->calculateProductPrice($this->grand_total,$this->currency_unit,$this->id);

    //     $productPrices['usd_price'] = number_format($productPrices['usd_price'],2);
    //     $productPrices['mmk_price'] = number_format($productPrices['mmk_price'],2);
    //     $productPrices['baht_price'] = number_format($productPrices['baht_price'],2);

    //     return $productPrices;
    // }

    //get currency rate from database
    private function getCurrentRate($from,$to,$orderId){
        $rate = OrderCurrencyRate::where('order_id',$orderId)->where('from',$from)->where('to',$to)->value('rate');
        if($rate){
            return $rate;
        }
        $inverseRate = OrderCurrencyRate::where('order_id',$orderId)->where('from',$to)->where('to',$from)->value('rate');
        if(!$inverseRate){
            return null;
        }
        $newRate = 1 / $inverseRate;
        return $newRate;
    }

    private function calculateProductPrice($mainPrice,$mainCurrencyUnit,$orderId){
        $mainPrice = $mainPrice + 0;
        $productPrices = [];
        if($mainCurrencyUnit == 'USD'){
            //get rate
            $bahtRate = $this->getCurrentRate($mainCurrencyUnit,'BAHT',$orderId);
            $mmkRate = $this->getCurrentRate($mainCurrencyUnit,'MMK',$orderId);

            //calculate
            $productPrices['usd_price'] = $mainPrice;
            $productPrices['mmk_price'] = $mainPrice * $mmkRate;
            $productPrices['baht_price'] = $mainPrice * $bahtRate;

            return $productPrices;
        }
        if($mainCurrencyUnit == 'BAHT'){
            $usdRate = $this->getCurrentRate($mainCurrencyUnit,'USD',$orderId);
            $mmkRate = $this->getCurrentRate($mainCurrencyUnit,'MMK',$orderId);

            //calculate
            $productPrices['usd_price'] = $mainPrice  * $usdRate;
            $productPrices['mmk_price'] = $mainPrice * $mmkRate;
            $productPrices['baht_price'] = $mainPrice;

            return $productPrices;
        }
        if($mainCurrencyUnit == 'MMK'){
            $bahtRate = $this->getCurrentRate($mainCurrencyUnit,'BAHT',$orderId);
            $usdRate = $this->getCurrentRate($mainCurrencyUnit,'USD',$orderId);

            //calculate
            $productPrices['usd_price'] =$mainPrice  * $usdRate;
            $productPrices['mmk_price'] = $mainPrice;
            $productPrices['baht_price'] = $mainPrice * $bahtRate;

            return $productPrices;
        }
    }
}
