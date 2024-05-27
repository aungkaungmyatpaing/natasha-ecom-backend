<?php

namespace App\Http\Controllers\API;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    //index
    public function index()
    {
        $data = Brand::orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'brands' => $data
        ]);
    }
}
