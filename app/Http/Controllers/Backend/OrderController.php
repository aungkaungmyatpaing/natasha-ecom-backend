<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CurrencyHistory;
use Carbon\Carbon;

class OrderController extends Controller
{
    //index
    public function index(){
        return view('backend.orders.index');
    }

    //detail
    public function detail($id){
        $order = Order::with('orderItem','orderItem.product','orderItem.product.image','payment','customer','orderCurrencyRate')->where('id',$id)->first();
        $currency_histories = CurrencyHistory::where('order_id',$order->id)->orderBy('id','desc')->get();
        // if($order->preorder_date){
        //     dd($order->changeDateFormat(Carbon::parse($order->preorder_date)));
        // }
        return view('backend.orders.detail')->with(['order'=>$order,'currency_histories'=>$currency_histories]);
    }

    //update order status
    public function updateStatus($id,Request $request){
        Order::where('id',$id)->update([
            'status'=> $request->status,
        ]);
        return response()->json([
            'message' => 'Order updated successfully',
        ]);
    }

    //pending order
    public function pendingOrder(){
        return view('backend.orders.pending-order');
    }

    //preorder
    public function preorder(){
        return view('backend.orders.preorder');
    }

    //get preorder datatable
    public function getPreOrder(){
        return $this->serverSide(null,true);
    }

    //all order datatable
    public function getAllOrder(){
        return $this->serverSide(null);
    }

    //get pending order datatable
    public function getPendingOrder(){
        return $this->serverSide('pending');
    }

    //data table
    private function serverSide($orderStatus,$preorder = null){
        if($orderStatus){
            $order = Order::where('status',$orderStatus)->orderBy('id','desc');
        } else if ($preorder){
            $order = Order::where('preorder_date','!=',null)->orderBy('id','desc');
        } else {
            $order = Order::orderBy('id','desc');
        }

        return datatables($order)
        ->addColumn('created_at',function($each){
            return $each->created_at->diffForHumans() ?? '-';
        })
        ->addColumn('preorder_date',function($each){
            if(!$each->preorder_date){
                return '---';
            }
            return '<div class="badge bg-success rounded-circle text-center"><i class="ri-check-line my-0" style="font-size: 15px;"></i></div>';
        })
        ->addColumn('status',function($each){
            if($each->status == "pending"){
                $status = 'bg-danger';
            }elseif($each->status == "finish"){
                $status = 'bg-success';
            }elseif($each->status == "cancel"){
                $status = 'bg-dark';
            }else{
                $status = 'bg-info';
            }
            return '<div class="badge '.$status.'">'.$each->status.'</div>';
        })
        ->addColumn('action', function ($each) {
            $show_icon = '<a href="'.route('order.detail', $each->id).'" class="show_btn mr-3"><i class="ri-eye-fill"></i></a>';
            $cancel_btn = '<a href="#" class="btn btn-dark cancelBtn"  data-status="cancel" data-id="'.$each->id  .'">Cancel</a>';
            if($each->status == 'cancel'){
                return '<div class="action_icon d-flex align-items-center">'. $show_icon .'</div>';
            }
            return '<div class="action_icon d-flex align-items-center">'. $show_icon . $cancel_btn .'</div>';
        })
        ->rawColumns(['name','address','payment_method','created_at','status','action','preorder_date'])
        ->toJson();
    }
}