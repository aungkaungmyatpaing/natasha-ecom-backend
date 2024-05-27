<?php

namespace App\Http\Controllers\Backend;

use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $topProducts = Product::
                        select('products.*', DB::raw('COUNT(order_items.product_id) as total_sales'))
                        ->join('order_items','products.id','order_items.product_id')
                        ->groupBy('order_items.product_id')
                        ->with('images')
                        ->active()
                        ->orderBy('total_sales','desc')
                        ->limit(5)
                        ->get();
        $topCustomers = Customer::select('customers.*',DB::raw('COUNT(orders.customer_id) as total_sales'))
                        ->join('orders','orders.customer_id','customers.id')
                        ->groupBy('customers.id')
                        ->orderBy('total_sales','desc')
                        ->limit(5)
                        ->get();
                        // dd($topCustomers->toArray());
        return view('backend.dashboard.index')->with(['topProducts'=> $topProducts,'topCustomers'=>$topCustomers]);
    }
}
