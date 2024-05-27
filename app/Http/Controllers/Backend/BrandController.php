<?php

namespace App\Http\Controllers\Backend;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBrandRequest;

class BrandController extends Controller
{
    /**
      * product listing view
      *
      * @return void
      */
    public function index()
    {
        return view('backend.brands.index');
    }

     /**
     * Create Form
     *
     * @return void
     */
    public function create()
    {
        return view('backend.brands.create');
    }

    /**
     * Store Category
     *
     * @param StoreCategoryRequest $request
     * @return void
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = new Brand();
        $brand->name = $request->name;
        if ($request->hasFile('image')) {
            $brand->image = $request->file('image')->store('brands');
        }
        $brand->save();

        return redirect()->route('brand')->with('created', 'Brand created Successfully');
    }

    /**
     * Product Categeory Edit
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('backend.brands.edit', compact('brand'));
    }

    /**
     * Product Category Update
     *
     * @param Reqeuest $reqeuest
     * @param [type] $id
     * @return void
     */
    public function update(StoreBrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;
        if ($request->hasFile('image')) {
            $oldImage = $brand->getRawOriginal('image') ?? '';
            Storage::delete($oldImage);
            $brand->image = $request->file('image')->store('brands');
        }
        $brand->update();

        return redirect()->route('brand')->with('updated', 'Brand Updated Successfully');
    }


    /**
     * delete Category
     *
     * @return void
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $oldImage = $brand->getRawOriginal('image') ?? '';
        Storage::delete($oldImage);

        $brand->delete();

        return 'success';
    }

     /**
     * ServerSide
     *
     * @return void
     */
    public function serverSide()
    {
        $brand = Brand::query();
        return datatables($brand)
        ->addColumn('image', function ($each) {
            return '<img src="'.$each->image.'" class="thumbnail_img"/>';
        })
        ->addColumn('action', function ($each) {
            $edit_icon = '<a href="'.route('brand.edit', $each->id).'" class="edit_btn mr-3"><i class="ri-edit-box-fill"></i></a>';
            $delete_icon = '<a href="#" class="delete_btn" data-id="'.$each->id.'"><i class="ri-delete-bin-fill"></i></a>';

            return '<div class="action_icon">'.$edit_icon. $delete_icon .'</div>';
        })
        ->rawColumns(['image', 'action'])
        ->toJson();
    }
}