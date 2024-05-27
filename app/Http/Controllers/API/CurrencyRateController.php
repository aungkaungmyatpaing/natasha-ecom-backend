<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    public function index(){
        $currencyRate = CurrencyRate::get();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'currencies' => $currencyRate,
        ]);
    }
}