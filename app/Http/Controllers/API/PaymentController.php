<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //payments
    public function index()
    {
        $payments = Payment::where('status', '1')->orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'payments' => $payments,
        ]);
    }
}
