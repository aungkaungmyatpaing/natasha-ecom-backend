<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    //index
    public function index()
    {
        $customer = Auth::guard('api')->user();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'customer' => $customer,
        ]);
    }
   //update
   public function update(Request $request)
   {
       $request->validate([
           'name' => 'required',
           'email' => 'required|email|unique:customers,email,'.Auth::guard('api')->user()->id,
       ]);
       $customer = Auth::guard('api')->user();
       if (!empty($customer)) {
           $customer->update([
               'name' => $request->name,
               'email' => $request->email,
           ]);
       }
       return response()->json([
           'status'=> '200',
           'success' => true,
           'message' => 'Your account has been updated,successfully!',
           'customer' => $customer->refresh(),
       ]);
   }

     //updatePassword
     public function updatePassword(Request $request)
     {
         $request->validate([
             'old_password' => 'required',
             'password' => 'required|min:6|confirmed',
         ]);
         $customer = Auth::guard('api')->user();
         if (!empty($customer)) {
             if (Hash::check($request->old_password, $customer->password)) {
                 $customer->update([
                     'password' => Hash::make($request->password)
                 ]);
                 return response()->json([
                     'status' => '200',
                     'success' => true,
                     'message' => 'Your password has been updated,successfully',
                 ]);
             } else {
                 return response()->json([
                     'status' => '200',
                     'success' => false,
                     'message' => 'Your old password does not match!',
                 ]);
             }
         }
         return response()->json([
             'status' => '200',
             'success' => false,
             'message' => 'There is no such data!',
         ]);
     }
}