<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CurrencyRate;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'size' => 'array',
    ];

    protected $appends = [
        'currencies'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function image(){
        return $this->hasOne(ProductImage::class,'product_id','id')->latestOfMany();
    }

    public function scopeActive($query){
        return $query->where('status','1');
    }

    public function getCurrenciesAttribute(){
        $productPrices = $this->calculateProductPrice($this->price,$this->main_currency_unit);

        $productPrices['usd_price'] = number_format($productPrices['usd_price'],2);
        $productPrices['mmk_price'] = number_format($productPrices['mmk_price'],2);
        $productPrices['baht_price'] = number_format($productPrices['baht_price'],2);

        return $productPrices;
    }

    ///get currency rate from database
    private function getCurrentRate($from,$to){
        $rate = CurrencyRate::where('from_currency_unit',$from)->where('currency_unit',$to)->value('currency_rate');
        if($rate){
            return $rate;
        }
        $inverseRate = CurrencyRate::where('from_currency_unit',$to)->where('currency_unit',$from)->value('currency_rate');
        if(!$inverseRate){
            return null;
        }
        $newRate = 1 / $inverseRate;
        return $newRate;
    }

    //get all product prices on currency
    private function calculateProductPrice($mainPrice,$mainCurrencyUnit){
        $mainPrice = $mainPrice + 0;
        $productPrices = [];
        if($mainCurrencyUnit == 'USD'){
            //get rate
            $bahtRate = $this->getCurrentRate($mainCurrencyUnit,'BAHT');
            $mmkRate = $this->getCurrentRate($mainCurrencyUnit,'MMK');

            //calculate
            $productPrices['usd_price'] = $mainPrice;
            $productPrices['mmk_price'] = $mainPrice * $mmkRate;
            $productPrices['baht_price'] = $mainPrice * $bahtRate;

            return $productPrices;
        }
        if($mainCurrencyUnit == 'BAHT'){
            $usdRate = $this->getCurrentRate($mainCurrencyUnit,'USD');
            $mmkRate = $this->getCurrentRate($mainCurrencyUnit,'MMK');

            //calculate
            $productPrices['usd_price'] = $mainPrice  * $usdRate;
            $productPrices['mmk_price'] = $mainPrice * $mmkRate;
            $productPrices['baht_price'] = $mainPrice;

            return $productPrices;
        }
        if($mainCurrencyUnit == 'MMK'){
            $bahtRate = $this->getCurrentRate($mainCurrencyUnit,'BAHT');
            $usdRate = $this->getCurrentRate($mainCurrencyUnit,'USD');

            //calculate
            $productPrices['usd_price'] =$mainPrice  * $usdRate;
            $productPrices['mmk_price'] = $mainPrice;
            $productPrices['baht_price'] = $mainPrice * $bahtRate;

            return $productPrices;
        }
    }
}