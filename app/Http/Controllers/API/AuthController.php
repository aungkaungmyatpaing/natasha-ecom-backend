<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return response()->json([
                'status' => '200',
                'success' => false,
                'message' => 'Customer Not Found',
                'customer' => null,
                'token' => null,
            ]);
        }

        if ($customer->is_banned == '1') {
            return response()->json([
                'status' => '200',
                'success' => false,
                'message' => 'Your account has been banned by admin.',
                'customer' => null,
                'token' => null,
            ]);
        }

        $hashPassword = $customer->password;
        if (Hash::check($request->password, $hashPassword)) {
            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'success',
                'customer' => $customer,
                'token' =>  $customer->createToken('Narita')->accessToken,
            ]);
        } else {
            return response()->json([
                'status' => '200',
                'success' => false,
                'message' => 'Crediantials do not match',
                'customer' => null,
                'token' => null,
            ]);
        }
    }

    //register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:customers,email',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'customer' => $customer,
            'token' =>  $customer->createToken('Narita')->accessToken,
        ]);
    }

    //logout
    public function logout()
    {
        $customer = Auth::guard('api')->user()->token();
        $customer->revoke();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'Logout successfully',
        ]);
    }

    //Delete
    public function delete(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);
        $customer = Auth::guard('api')->user();

        $hashPassword = $customer->password;
        if (Hash::check($request->password, $hashPassword)) {
            $customer->delete();
            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'status' => '200',
                'success' => false,
                'message' => 'Crediantials do not match',
            ]);
        }
    }
}
