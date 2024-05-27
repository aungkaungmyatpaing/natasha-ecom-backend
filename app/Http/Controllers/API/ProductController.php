<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    //listing
    public function listing(Request $request) {
        $request->validate([
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
        ]);

        $query = Product::active();

        if(isset($request->category_id)) {
            $query = $query->where('category_id', $request->category_id);
        }

        if(isset($request->brand_id)) {
            $query = $query->where('brand_id', $request->brand_id);
        }

        if(isset($request->search_key)){
            $query = $query->where( function ($query) use ($request) {
                $query->orWhere('name','like','%'.$request->search_key.'%')->orWhere('price','like','%'.$request->search_key.'%');
            });
        }

        $result = $query->orderBy('id','DESC')->paginate($request->limit);

        foreach($result as $key => $product){
            $image = ProductImage::select('path')->where('product_id',$product->id)->first();
            $result[$key]['image'] = $image->toArray();
        }

        $totalPages = ceil($result->total() / $request->limit);

        return response()->json([
            'status' => '200',
            'message' => 'success',
            'total' => $result->total(),
            'can_load_more' => $result->total() == 0 || $request->page == $totalPages ? false : true,
            'data' => $result->getCollection()
         ]);
    }

    //product detail
    public function detail($id){
        $product = Product::where('id',$id)->with('brand','category','images')->get();
        if(!empty($product)){
            return response()->json([
                'status' => '200',
                'message' => 'success',
                'product' => $product,
            ]);
        }
        return response()->json([
            'status' => '200',
            'message' => 'fail',
            'product' => $product,
        ]);
    }

    //product images
    public function productImages($id){
        $images = ProductImage::where('product_id',$id)->get();
        if($images->count() == 0){
            return response()->json([
                'status' => '200',
                'success' => 'false',
                'message'=> 'Product id does not match',
            ]);
        }
        return response()->json([
                'status' => '200',
                'success' => 'true',
                'message'=> 'success',
                'images' => $images
        ]);
    }

}