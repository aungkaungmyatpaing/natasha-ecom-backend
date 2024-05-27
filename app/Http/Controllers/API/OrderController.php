<?php

namespace App\Http\Controllers\API;

use Exception;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;
use App\Models\OrderCurrencyRate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderItemResource;
use App\Models\CurrencyHistory;

class OrderController extends Controller
{
    public $mmkRate;
    public $bahtRate;

    //list
    public function list()
    {
        $orders = Order::with('orderItem', 'orderItem.product','orderItem.product.image', 'payment','orderCurrencyRate')->where('preorder_date',null)->where('customer_id', Auth::guard('api')->user()->id)->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'orders' => OrderResource::collection($orders),
        ]);
    }

    //detail
    public function detail($id)
    {
        // $order = Order::with('orderItem', 'orderItem.product','orderItem.product.image', 'payment','orderCurrencyRate')->where('customer_id', Auth::guard('api')->user()->id)->where('id', $id)->get();

        $order = Order::where('customer_id', Auth::guard('api')->user()->id)->where('id', $id)->latest()->first();

        if (!empty($order)) {
            $order_array = [
                'id' => $order->id,
                'preorder_date' => $order->preorder_date,
                'customer_id' => $order->customer_id,
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
                'currency_unit' => $order->currency_unit,
                'user_selected_currency' => $order->user_selected_currency,
                'usd_grand_total' => $order->usd_grand_total,
                'baht_grand_total' => $order->baht_grand_total,
                'mmk_grand_total' => $order->mmk_grand_total,
                'order_items' => OrderItemResource::collection($order->orderItem),
            ];

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'success',
                'order' => $order_array,
            ]);
        }
        return response()->json([
            'status' => '200',
            'success' => false,
            'message' => 'fail',
            'order' => $order,
        ]);
    }

    //create order
    public function create(Request $request)
    {
        //validation
        $rules = $this->validateRequestOrderData($request);
        $request->validate($rules);

        //cart product check
        $cartsCheck = json_decode($request->carts);
        foreach ($cartsCheck as $singleCart) {
            $product = Product::active()->where('id',$singleCart->product_id)->first();
            if(!$product){
                return response()->json([
                    'status' => '200',
                    'success' => false,
                    'message' => 'Ordered Product not Found!'
                ]);
            }
             if($product->active_currency != "ALL"){
                 if($product->active_currency != $request->currency_unit){
                    return response()->json([
                        'status' => '200',
                        'success' => false,
                        'message' => $product->name.' is not available for '.$request->currency_unit
                    ]);
                 }
             }

         }


        if ($request->payment_method !== 'payment' && $request->payment_method !== 'cod') {
            return response()->json([
                'status' => '200',
                'success' => false,
                'message' => 'Payment Method Error'
            ]);
        }

        if ($request->payment_method == 'payment') {
            $payment = Payment::find($request->payment_id);
            if (!$payment) {
                return response()->json([
                    'status' => '200',
                    'success' => false,
                    'message' => 'Payment Provider Error'
                ]);
            }
            if(!in_array($request->currency_unit,$payment->available_currency)){
                return response()->json([
                    'status' => '200',
                    'success' => false,
                    'message' => 'This Payment Provider does not support for '.$request->currency_unit
                ]);
            }
        }

        // $currencyCheck = CurrencyRate::where('from_currency_unit',$request->currency_unit)->exists();
        // if(!$currencyCheck){
        //     $inverseCurrencyCheck = CurrencyRate::where('currency_unit',$request->currency_unit)->exists();
        //     if(!$inverseCurrencyCheck){
        //         return response()->json([
        //             'status' => '200',
        //             'success' => false,
        //             'message' => 'Currency Rate Error!'
        //         ]);
        //     }
        // }


        DB::beginTransaction();
        try {
            //get data
            //$orderData = $this->getRequestOrderData($request);

            $carts = json_decode($request->carts);
            $usd_grand_total = 0;$baht_grand_total = 0;$mmk_grand_total = 0;
            foreach ($carts as $cart) {
                $product = Product::find($cart->product_id);
                if($product->main_currency_unit == 'USD') {
                    $baht_exchange_rate = CurrencyRate::where('from_currency_unit','USD')->where('currency_unit','BAHT')->latest()->first();
                    $mmk_exchange_rate = CurrencyRate::where('from_currency_unit','USD')->where('currency_unit','MMK')->latest()->first();
                    $product_price_usd = $product->price;
                    $product_price_mmk = (float)$product->price * (float)$mmk_exchange_rate->currency_rate;
                    $product_price_baht = (float)$product->price * (float)$baht_exchange_rate->currency_rate;
                }

                if($product->main_currency_unit == 'BAHT') {
                    $usd_exchange_rate = CurrencyRate::where('from_currency_unit','BAHT')->where('currency_unit','USD')->latest()->first();
                    $mmk_exchange_rate = CurrencyRate::where('from_currency_unit','BAHT')->where('currency_unit','MMK')->latest()->first();
                    $product_price_usd = (float)$product->price * (float)$usd_exchange_rate->currency_rate; 
                    $product_price_mmk = (float)$product->price * (float)$mmk_exchange_rate->currency_rate;
                    $product_price_baht = $product->price;
                }

                if($product->main_currency_unit == 'MMK') {
                    $usd_exchange_rate = CurrencyRate::where('from_currency_unit','MMK')->where('currency_unit','USD')->latest()->first();
                    $baht_exchange_rate = CurrencyRate::where('from_currency_unit','MMK')->where('currency_unit','BAHT')->latest()->first();
                    $product_price_usd = (float)$product->price * (float)$usd_exchange_rate->currency_rate; 
                    $product_price_mmk = $product->price;
                    $product_price_baht = (float)$product->price * (float)$baht_exchange_rate->currency_rate;
                }

                $usd_grand_total = $usd_grand_total + ($product_price_usd * $cart->quantity);
                $baht_grand_total = $baht_grand_total + ($product_price_baht * $cart->quantity);
                $mmk_grand_total = $mmk_grand_total + ($product_price_mmk * $cart->quantity);
            }

            //$order = Order::create($orderData);
            $order = new Order();
            $order->customer_id = Auth::guard('api')->user()->id;
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->payment_method = $request->payment_method;
            $order->status = 'pending';
            $order->currency_unit = $request->currency_unit;
            $order->user_selected_currency = $request->user_selected_currency;
            $order->usd_grand_total = $usd_grand_total; // new
            $order->baht_grand_total = $baht_grand_total; // new
            $order->mmk_grand_total = $mmk_grand_total; // new

            // if payment photo slip exist
            if ($request->payment_method == 'payment') {
                $order->payment_id = $request->payment_id;
                if ($request->hasFile('payment_photo')) {
                    $image = $request->file('payment_photo');
                    $order->payment_photo = $image->store('payment-photos');
                }
            }

            $order->save();

            // Currency History
            $currencies = CurrencyRate::orderBy('created_at','desc')->get();
            foreach($currencies as $currency) {
                $currency_history = new CurrencyHistory();
                $currency_history->order_id = $order->id;
                $currency_history->currency_rate = $currency->currency_rate;
                $currency_history->currency_unit = $currency->currency_unit;
                $currency_history->from_currency_unit = $currency->from_currency_unit;
                $currency_history->save();
            }

            // //currencies
            // $orderCurrencyRateData = $this->getOrderCurrencyRateData($request->currency_unit,$order->id);
            // OrderCurrencyRate::insert($orderCurrencyRateData);

            //order items
            $carts = json_decode($request->carts);
            foreach ($carts as $cart) {


                $product = Product::find($cart->product_id);
                $product = Product::find($cart->product_id);
                if($product->main_currency_unit == 'USD') {
                    $baht_exchange_rate = CurrencyRate::where('from_currency_unit','USD')->where('currency_unit','BAHT')->latest()->first();
                    $mmk_exchange_rate = CurrencyRate::where('from_currency_unit','USD')->where('currency_unit','MMK')->latest()->first();
                    $product_price_usd = $product->price;
                    $product_price_mmk = (float)$product->price * (float)$mmk_exchange_rate->currency_rate;
                    $product_price_baht = (float)$product->price * (float)$baht_exchange_rate->currency_rate;
                }

                if($product->main_currency_unit == 'BAHT') {
                    $usd_exchange_rate = CurrencyRate::where('from_currency_unit','BAHT')->where('currency_unit','USD')->latest()->first();
                    $mmk_exchange_rate = CurrencyRate::where('from_currency_unit','BAHT')->where('currency_unit','MMK')->latest()->first();
                    $product_price_usd = (float)$product->price * (float)$usd_exchange_rate->currency_rate; 
                    $product_price_mmk = (float)$product->price * (float)$mmk_exchange_rate->currency_rate;
                    $product_price_baht = $product->price;
                }

                if($product->main_currency_unit == 'MMK') {
                    $usd_exchange_rate = CurrencyRate::where('from_currency_unit','MMK')->where('currency_unit','USD')->latest()->first();
                    $baht_exchange_rate = CurrencyRate::where('from_currency_unit','MMK')->where('currency_unit','BAHT')->latest()->first();
                    $product_price_usd = (float)$product->price * (float)$usd_exchange_rate->currency_rate; 
                    $product_price_mmk = $product->price;
                    $product_price_baht = (float)$product->price * (float)$baht_exchange_rate->currency_rate;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    //'price' => $product->price,
                    'usd_price' => $product_price_usd,
                    'baht_price' => $product_price_baht,
                    'mmk_price' => $product_price_mmk,
                    'main_currency_unit' => $product->main_currency_unit, // new
                    'quantity' => $cart->quantity,
                    'usd_total_price' => $cart->quantity * $product_price_usd, // new
                    'baht_total_price' => $cart->quantity * $product_price_baht, // new
                    'mmk_total_price' => $cart->quantity * $product_price_mmk // new
                ]);
            }
            DB::commit();

            $order_array = [
                'id' => $order->id,
                'customer_id' => $order->customer_id,
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
                'currency_unit' => $order->currency_unit,
                'user_selected_currency' => $order->user_selected_currency,
                'usd_grand_total' => $order->usd_grand_total,
                'baht_grand_total' => $order->baht_grand_total,
                'mmk_grand_total' => $order->mmk_grand_total,
                'order_items' => OrderItemResource::collection($order->orderItem),
            ];

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Order placed successfully',
                'order' => $order_array,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    //preorder
    public function preorder(Request $request){

        //validation
        $rules = $this->validateRequestOrderData($request);
        $request->validate($rules);

        //cart product check
        $cartsCheck = json_decode($request->carts);
        foreach ($cartsCheck as $singleCart) {
            $product = Product::active()->where('id',$singleCart->product_id)->first();
            if(!$product){
                return response()->json([
                    'status' => '200',
                    'success' => false,
                    'message' => 'Ordered Product not Found!'
                ]);
            }
             if($product->active_currency != "ALL"){
                 if($product->active_currency != $request->currency_unit){
                    return response()->json([
                        'status' => '200',
                        'success' => false,
                        'message' => $product->name.' is not available for '.$request->currency_unit
                    ]);
                 }
             }

         }


        if ($request->payment_method !== 'payment' && $request->payment_method !== 'cod') {
            return response()->json([
                'status' => '200',
                'success' => false,
                'message' => 'Payment Method Error'
            ]);
        }

        if ($request->payment_method == 'payment') {
            $payment = Payment::find($request->payment_id);
            if (!$payment) {
                return response()->json([
                    'status' => '200',
                    'success' => false,
                    'message' => 'Payment Provider Error'
                ]);
            }
            if(!in_array($request->currency_unit,$payment->available_currency)){
                return response()->json([
                    'status' => '200',
                    'success' => false,
                    'message' => 'This Payment Provider does not support for '.$request->currency_unit
                ]);
            }
        }

        // $currencyCheck = CurrencyRate::where('from_currency_unit',$request->currency_unit)->exists();
        // if(!$currencyCheck){
        //     $inverseCurrencyCheck = CurrencyRate::where('currency_unit',$request->currency_unit)->exists();
        //     if(!$inverseCurrencyCheck){
        //         return response()->json([
        //             'status' => '200',
        //             'success' => false,
        //             'message' => 'Currency Rate Error!'
        //         ]);
        //     }
        // }


        DB::beginTransaction();
        try {
            //get data
            //$orderData = $this->getRequestOrderData($request);

            $carts = json_decode($request->carts);
            $usd_grand_total = 0;$baht_grand_total = 0;$mmk_grand_total = 0;
            foreach ($carts as $cart) {
                $product = Product::find($cart->product_id);
                if($product->main_currency_unit == 'USD') {
                    $baht_exchange_rate = CurrencyRate::where('from_currency_unit','USD')->where('currency_unit','BAHT')->latest()->first();
                    $mmk_exchange_rate = CurrencyRate::where('from_currency_unit','USD')->where('currency_unit','MMK')->latest()->first();
                    $product_price_usd = $product->price;
                    $product_price_mmk = (float)$product->price * (float)$mmk_exchange_rate->currency_rate;
                    $product_price_baht = (float)$product->price * (float)$baht_exchange_rate->currency_rate;
                }

                if($product->main_currency_unit == 'BAHT') {
                    $usd_exchange_rate = CurrencyRate::where('from_currency_unit','BAHT')->where('currency_unit','USD')->latest()->first();
                    $mmk_exchange_rate = CurrencyRate::where('from_currency_unit','BAHT')->where('currency_unit','MMK')->latest()->first();
                    $product_price_usd = (float)$product->price * (float)$usd_exchange_rate->currency_rate; 
                    $product_price_mmk = (float)$product->price * (float)$mmk_exchange_rate->currency_rate;
                    $product_price_baht = $product->price;
                }

                if($product->main_currency_unit == 'MMK') {
                    $usd_exchange_rate = CurrencyRate::where('from_currency_unit','MMK')->where('currency_unit','USD')->latest()->first();
                    $baht_exchange_rate = CurrencyRate::where('from_currency_unit','MMK')->where('currency_unit','BAHT')->latest()->first();
                    $product_price_usd = (float)$product->price * (float)$usd_exchange_rate->currency_rate; 
                    $product_price_mmk = $product->price;
                    $product_price_baht = (float)$product->price * (float)$baht_exchange_rate->currency_rate;
                }

                $usd_grand_total = $usd_grand_total + ($product_price_usd * $cart->quantity);
                $baht_grand_total = $baht_grand_total + ($product_price_baht * $cart->quantity);
                $mmk_grand_total = $mmk_grand_total + ($product_price_mmk * $cart->quantity);
            }

            //$order = Order::create($orderData);
            $order = new Order();
            $order->customer_id = Auth::guard('api')->user()->id;
            $order->preorder_date = now();
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->payment_method = $request->payment_method;
            $order->status = 'pending';
            $order->currency_unit = $request->currency_unit;
            $order->user_selected_currency = $request->user_selected_currency;
            $order->usd_grand_total = $usd_grand_total; // new
            $order->baht_grand_total = $baht_grand_total; // new
            $order->mmk_grand_total = $mmk_grand_total; // new

            // if payment photo slip exist
            if ($request->payment_method == 'payment') {
                $order->payment_id = $request->payment_id;
                if ($request->hasFile('payment_photo')) {
                    $image = $request->file('payment_photo');
                    $order->payment_photo = $image->store('payment-photos');
                }
            }

            $order->save();

            // Currency History
            $currencies = CurrencyRate::orderBy('created_at','desc')->get();
            foreach($currencies as $currency) {
                $currency_history = new CurrencyHistory();
                $currency_history->order_id = $order->id;
                $currency_history->currency_rate = $currency->currency_rate;
                $currency_history->currency_unit = $currency->currency_unit;
                $currency_history->from_currency_unit = $currency->from_currency_unit;
                $currency_history->save();
            }

            // //currencies
            // $orderCurrencyRateData = $this->getOrderCurrencyRateData($request->currency_unit,$order->id);
            // OrderCurrencyRate::insert($orderCurrencyRateData);

            //order items
            $carts = json_decode($request->carts);
            foreach ($carts as $cart) {


                $product = Product::find($cart->product_id);
                $product = Product::find($cart->product_id);
                if($product->main_currency_unit == 'USD') {
                    $baht_exchange_rate = CurrencyRate::where('from_currency_unit','USD')->where('currency_unit','BAHT')->latest()->first();
                    $mmk_exchange_rate = CurrencyRate::where('from_currency_unit','USD')->where('currency_unit','MMK')->latest()->first();
                    $product_price_usd = $product->price;
                    $product_price_mmk = (float)$product->price * (float)$mmk_exchange_rate->currency_rate;
                    $product_price_baht = (float)$product->price * (float)$baht_exchange_rate->currency_rate;
                }

                if($product->main_currency_unit == 'BAHT') {
                    $usd_exchange_rate = CurrencyRate::where('from_currency_unit','BAHT')->where('currency_unit','USD')->latest()->first();
                    $mmk_exchange_rate = CurrencyRate::where('from_currency_unit','BAHT')->where('currency_unit','MMK')->latest()->first();
                    $product_price_usd = (float)$product->price * (float)$usd_exchange_rate->currency_rate; 
                    $product_price_mmk = (float)$product->price * (float)$mmk_exchange_rate->currency_rate;
                    $product_price_baht = $product->price;
                }

                if($product->main_currency_unit == 'MMK') {
                    $usd_exchange_rate = CurrencyRate::where('from_currency_unit','MMK')->where('currency_unit','USD')->latest()->first();
                    $baht_exchange_rate = CurrencyRate::where('from_currency_unit','MMK')->where('currency_unit','BAHT')->latest()->first();
                    $product_price_usd = (float)$product->price * (float)$usd_exchange_rate->currency_rate; 
                    $product_price_mmk = $product->price;
                    $product_price_baht = (float)$product->price * (float)$baht_exchange_rate->currency_rate;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    //'price' => $product->price,
                    'usd_price' => $product_price_usd,
                    'baht_price' => $product_price_baht,
                    'mmk_price' => $product_price_mmk,
                    'main_currency_unit' => $product->main_currency_unit, // new
                    'quantity' => $cart->quantity,
                    'usd_total_price' => $cart->quantity * $product_price_usd, // new
                    'baht_total_price' => $cart->quantity * $product_price_baht, // new
                    'mmk_total_price' => $cart->quantity * $product_price_mmk // new
                ]);
            }
            DB::commit();

            $order_array = [
                'id' => $order->id,
                'preorder_date' => $order->preorder_date,
                'customer_id' => $order->customer_id,
                'name' => $order->name,
                'phone' => $order->phone,
                'address' => $order->address,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
                'currency_unit' => $order->currency_unit,
                'user_selected_currency' => $order->user_selected_currency,
                'usd_grand_total' => $order->usd_grand_total,
                'baht_grand_total' => $order->baht_grand_total,
                'mmk_grand_total' => $order->mmk_grand_total,
                'order_items' => OrderItemResource::collection($order->orderItem),
            ];

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Order placed successfully',
                'order' => $order_array,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function preorderList(){
        $orders = Order::where('preorder_date','!=',null)->where('customer_id', Auth::guard('api')->user()->id)->orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'orders' => OrderResource::collection($orders),
        ]);
    }

    //validate order data
    private function validateRequestOrderData($request)
    {
        $rules =[
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'payment_method' => 'required',
            'carts' => 'required|json',
            'currency_unit' => 'required|in:USD,MMK,BAHT,ALL',
            'user_selected_currency' => 'required',
        ];

        if ($request->payment_method == 'payment') {
            $rules['payment_id'] = 'required';
            $rules['payment_photo'] = 'required|image';
        }

        return $rules;
    }

    //get order data
    private function getRequestOrderData($request)
    {
        $data = [
            'customer_id' => Auth::guard('api')->user()->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'currency_unit' => $request->currency_unit,
            'user_selected_currency' => $request->user_selected_currency
        ];



        if ($request->payment_method == 'payment') {
            $data['payment_id'] = $request->payment_id;
            if ($request->hasFile('payment_photo')) {
                $image = $request->file('payment_photo');
                $data['payment_photo'] = $image->store('payment-photos');
            }
        }

        $grandTotal = 0;

        foreach (json_decode($request->carts) as $cart) {
            $grandTotal += $cart->total_price;
        }

        $data['grand_total'] = $grandTotal;

        return $data;
    }

    private function getOrderCurrencyRateData($requestCurrencyUnit,$orderId){
        if($requestCurrencyUnit == 'MMK'){
            $usdRate = $this->getCurrentRate($requestCurrencyUnit,'USD');
            $bahtRate = $this->getCurrentRate($requestCurrencyUnit,'BAHT');

            $orderCurrencyRates[] = [
                'order_id' => $orderId,
                'from' => $requestCurrencyUnit,
                'rate' => $usdRate,
                'to' => 'USD'
            ];
            $orderCurrencyRates[] = [
                'order_id' => $orderId,
                'from' => $requestCurrencyUnit,
                'rate' => $bahtRate,
                'to' => 'BAHT'
            ];

            return $orderCurrencyRates;

        }elseif($requestCurrencyUnit == 'BAHT'){

            $usdRate = $this->getCurrentRate($requestCurrencyUnit,'USD');
            $mmkRate = $this->getCurrentRate($requestCurrencyUnit,'MMK');

            $orderCurrencyRates[] = [
                'order_id' => $orderId,
                'from' => $requestCurrencyUnit,
                'rate' => $usdRate,
                'to' => 'USD'
            ];
            $orderCurrencyRates[] = [
                'order_id' => $orderId,
                'from' => $requestCurrencyUnit,
                'rate' => $mmkRate,
                'to' => 'MMK'
            ];

            return $orderCurrencyRates;
        }elseif($requestCurrencyUnit == 'USD'){

            $bahtRate = $this->getCurrentRate($requestCurrencyUnit,'BAHT');
            $mmkRate = $this->getCurrentRate($requestCurrencyUnit,'MMK');

            $orderCurrencyRates[] = [
                'order_id' => $orderId,
                'from' => $requestCurrencyUnit,
                'rate' => $bahtRate,
                'to' => 'BAHT'
            ];
            $orderCurrencyRates[] = [
                'order_id' => $orderId,
                'from' => $requestCurrencyUnit,
                'rate' => $mmkRate,
                'to' => 'MMK'
            ];

            return $orderCurrencyRates;
        }


    }


    //for grant total (3 units )
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

}