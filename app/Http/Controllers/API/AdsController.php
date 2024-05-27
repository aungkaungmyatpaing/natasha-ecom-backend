<?php

namespace App\Http\Controllers\API;

use App\Models\Ads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdsController extends Controller
{
    public function index(){
        $data = Ads::where('status',1)->orderBy('id','desc')->get();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'banners' => $data
        ]);
    }
}