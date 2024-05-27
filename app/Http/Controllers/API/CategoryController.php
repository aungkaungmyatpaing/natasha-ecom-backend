<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    //category
    public function index()
    {
        $data = Category::orderBy('id', 'DESC')->get();
        return response()->json([
            'status' => '200',
            'success' => true,
            'message' => 'success',
            'categories' => $data,
        ]);
    }
}
