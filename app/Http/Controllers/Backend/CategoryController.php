<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCategoryRequest;

class CategoryController extends Controller
{
    /**
     * product listing view
     *
     * @return void
     */
    public function index()
    {
        return view('backend.categories.index');
    }

     /**
     * Create Form
     *
     * @return void
     */
    public function create()
    {
        return view('backend.categories.create');
    }

    /**
     * Store Category
     *
     * @param StoreCategoryRequest $request
     * @return void
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();
        $category->name = $request->name;
        if ($request->hasFile('image')) {
        $category->image = $request->file('image')->store('categories');
        }
        $category->save();

        return redirect()->route('category')->with('created', 'Category created Successfully');
    }

    /**
     * Product Categeory Edit
     *
     * @param [type] $id
     * @return void
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('backend.categories.edit', compact('category'));
    }

    /**
     * Product Category Update
     *
     * @param Reqeuest $reqeuest
     * @param [type] $id
     * @return void
     */
    public function update(StoreCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        if ($request->hasFile('image')) {
            $oldImage = $category->getRawOriginal('image') ?? '';
            Storage::delete($oldImage);
            $category->image = $request->file('image')->store('categories');
        }
        $category->update();

        return redirect()->route('category')->with('updated', 'Category Updated Successfully');
    }


    /**
     * delete Category
     *
     * @return void
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $oldImage = $category->getRawOriginal('image') ?? '';
        Storage::delete($oldImage);

        $category->delete();

        return 'success';
    }

     /**
     * ServerSide
     *
     * @return void
     */
    public function serverSide()
    {
        $category = Category::query();
        return datatables($category)
        ->addColumn('image', function ($each) {
            return '<img src="'.$each->image.'" class="thumbnail_img"/>';
        })
        ->addColumn('action', function ($each) {
            $edit_icon = '<a href="'.route('category.edit', $each->id).'" class="edit_btn mr-3"><i class="ri-edit-box-fill"></i></a>';
            $delete_icon = '<a href="#" class="delete_btn" data-id="'.$each->id.'"><i class="ri-delete-bin-fill"></i></a>';

            return '<div class="action_icon">'.$edit_icon. $delete_icon .'</div>';
        })
        ->rawColumns(['image', 'action'])
        ->toJson();
    }
}