<?php

namespace App\Http\Controllers\API;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    //index
    public function index()
    {
        $data = Banner::orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'banners' => $data
        ]);
    }
}
