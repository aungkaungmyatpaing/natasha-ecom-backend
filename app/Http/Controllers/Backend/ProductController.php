<?php

namespace App\Http\Controllers\Backend;

use Exception;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\CurrencyRate;

class ProductController extends Controller
{
    public $productImageArray = [];
    /**
     * product listing view
     *
     * @return void
     */
    public function listing()
    {
        return view('backend.products.index');
    }

    /**
     * Product create
     *
     * @return void
     */
    public function create()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('id', 'desc')->get();

        return view('backend.products.create', compact('categories', 'brands'));
    }

     /**
     * Product Store
     *
     * @param Request $request
     * @return void
     */
    public function store(StoreProductRequest $request)
    {

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $request->name;

            $product->price = $request->price;
            $product->main_currency_unit = $request->main_currency_unit;
            $product->active_currency = $request->active_currency;

            $product->category_id = $request->category_id ?? null;
            $product->brand_id = $request->brand_id ?? null;
            $product->description = $request->description;
            $product->stock = $request->stock;
            $product->save();

            if ($request->hasFile('images')) {
                $this->_createProductImages($product->id, $request->file('images'));
            }

            DB::commit();
            return redirect()->route('product')->with('created', 'Product Created Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }



    /**
     * Product detail
     *
     */
    public function detail($id){
        $product = Product::with('brand','category','images')->where('id',$id)->first();
            // $productPrices = $this->calculateProductPrice($product->price,$product->main_currency_unit);
            // $product->currencies = $productPrices;
        // dd(is_float($product->currencies['baht_price']));
        return view('backend.products.detail')->with(['product'=>$product]);
    }

    /**
   * Create Review Images
   */
  private function _createProductImages($productId, $files)
  {
      foreach ($files as $image) {
          $this->productImageArray[] = [
              'product_id'      => $productId,
              'path'           => $image->store('products'),
              'created_at'     => now(),
              'updated_at'     => now(),
          ];
      }

      ProductImage::insert($this->productImageArray);
  }

    /**
     * Product edit
     *
     * @param StoreProductRequest $request
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('id', 'desc')->get();
        $brands = Brand::orderBy('id', 'desc')->get();
        return view('backend.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update Product
     *
     * @param [type] $id
     * @param StoreProductRequest $request
     * @return void
     */
    public function update($id, UpdateProductRequest $request)
    {
        if (empty($request->old) && empty($request->images)) {
            return redirect()->back()->with('fail', 'Product Image is required');
        }

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->main_currency_unit = $request->main_currency_unit;
            $product->active_currency = $request->active_currency;

            $product->category_id = $request->category_id ?? null;
            $product->brand_id = $request->brand_id ?? null;
            $product->description = $request->description;
            $product->stock = $request->stock;
            $product->update();

            // old image file delete
            if ($request->has('old')) {
                $files = $product->images()->whereNotIn('id', $request->old)->get();## oldimg where not in request old
                if (count($files) > 0) { ## delete oldimg where not in request old
                    foreach ($files as $file) {
                        $oldPath = $file->getRawOriginal('path') ?? '';
                        Storage::delete($oldPath);
                    }

                    $product->images()->whereNotIn('id', $request->old)->delete();
                }
            }

            if ($request->hasFile('images')) {
                $this->_createProductImages($product->id, $request->file('images'));
            }

            DB::commit();
            return redirect()->route('product')->with('updated', 'Product Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

     /**
     * Product destroy
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        Product::where('id',$id)->update([
            'status' => '0'
        ]);
        return 'success';
    }

     /**
     * ServerSide
     *
     * @return void
     */
    public function serverSide()
    {
        $product = Product::active();
        return datatables($product)
        ->addColumn('image', function ($each) {
            $image = $each->images()->first();
            return '<img src="'.$image->path.'" class="thumbnail_img"/>';
        })
        ->addColumn('category', function ($each) {
            return $each->category->name ?? '---';
        })
        ->addColumn('brand', function ($each) {
            return $each->brand->name ?? '---';
        })
        ->addColumn('price',function($each){
            if($each->main_currency_unit == 'MMK'){
                return $each->price.' MMK';
            }elseif($each->main_currency_unit == 'BAHT'){
                return $each->price.' BAHT';
            }else{
                return $each->price.' USD';
            }
        })
        ->editColumn('stock',function($each){
            return $each->stock == 1 ? '<div class="badge bg-success">Instock</div>' : '<div class="badge bg-danger">Out of stock</div>';
        })
        ->addColumn('action', function ($each) {
            $show_icon = '<a href="'.route('product.detail', $each->id).'" class="detail_btn mr-3"><i class="ri-eye-fill"></i></a>';
            $edit_icon = '<a href="'.route('product.edit', $each->id).'" class="edit_btn mr-3"><i class="ri-edit-box-fill"></i></a>';
            $delete_icon = '<a href="#" class="delete_btn" data-id="'.$each->id.'"><i class="ri-delete-bin-fill"></i></a>';

            return '<div class="action_icon">'. $show_icon .$edit_icon. $delete_icon .'</div>';
        })
        ->rawColumns(['category', 'brand', 'action', 'image','stock','price'])
        ->toJson();
    }

    /**
     * Product images
     *
     * @return void
     */
    public function images($id)
    {
        $product   = Product::findOrFail($id);
        $oldImages = [];
        foreach ($product->images as $img) {
            $oldImages[] = [
            'id'  => $img->id,
            'src' => $img->path,
          ];
        }

        return response()->json($oldImages);
    }

}