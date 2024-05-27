<?php

namespace App\Http\Controllers\Backend;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Requests\UpdateCustomerPasswordRequest;

class CustomerController extends Controller
{
    //index
    public function index(){
        return view('backend.customers.index');
    }

    //edit
    public function edit($id){
        $customer = Customer::where('id',$id)->first();
        return view('backend.customers.edit')->with(['customer' => $customer]);
    }

    //update
    public function update(UpdateCustomerRequest $request,$id){
        Customer::where('id',$id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return redirect()->route('customer')->with(['updated'=>'Customer updated successfully']);
    }

    //updatePassword
    public function updatePassword(UpdateCustomerPasswordRequest $request,$id){
        Customer::where('id',$id)->update([
            'password' => Hash::make($request->password)
        ]);
        return redirect()->route('customer')->with(['updated'=>'Customer updated successfully']);
    }

    //detail
    public function detail($id){
        $customer = Customer::where('id',$id)->first();
        return view('backend.customers.detail')->with(['customer'=> $customer]);
    }

    //ban customer
    public function banCustomer($id,Request $request){
        $customer = Customer::where('id',$id)->first();
        $customer->update([
            'is_banned' => $request->is_banned,
        ]);
        return response()->json([
            'customerName' => $customer->name,
        ]);
    }

    //server side
    public function serverSide()
    {
        $customers = Customer::query();
        return datatables($customers)

        ->addColumn('action', function ($each) {
            $show_icon = '<a href="'.route('customer.detail', $each->id).'" class="detail_btn mr-3"><i class="ri-eye-fill"></i></a>';
            $edit_icon = '<a href="'.route('customer.edit', $each->id).'" class="edit_btn mr-3"><i class="ri-edit-box-fill"></i></a>';

            return '<div class="action_icon">'. $show_icon .$edit_icon.'</div>';
        })
        ->addColumn('is_banned', function ($each) {
            if($each->is_banned == '0'){
                $ban_btn = '<a href="#" class="btn btn-danger ban_btn"  data-id="'.$each->id  .'">Ban</a>';
            }else{
                $ban_btn = '<a href="#" class="btn btn-outline-danger unban_btn"  data-id="'.$each->id  .'">unBan</a>';
            }
            return '<div class="action_icon">'. $ban_btn .'</div>';
        })
        ->rawColumns(['action','is_banned'])
        ->toJson();
    }
}
