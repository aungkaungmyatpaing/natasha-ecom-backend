<?php

namespace App\Http\Controllers\Backend;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreBannerRequest;

class BannerController extends Controller
{
    //index
    public function index(){
        return view('backend.banners.index');
    }

    //create
    public function create(){
        return view('backend.banners.create');
    }

    //store
    public function store(StoreBannerRequest $request){
        //validation
        $request->validate([
            'title' => 'required',
        ]);
        $data = [
            'title' => $request->title,
        ];

        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('banners');
        }

        Banner::create($data);
        return redirect()->route('banner')->with(['created'=>'Banner created successfully']);
    }
    //edit
    public function edit($id){
        $banner = Banner::where('id',$id)->first();
        return view('backend.banners.edit')->with(['banner'=>$banner]);
    }

    //update
    public function update(StoreBannerRequest $request,$id){
        $request->validate([
            'title' => 'required',
        ]);
        $updatedData = [
            'title' => $request->title,
        ];
        if($request->hasFile('image')){
            //delete old img
            $banner = Banner::where('id',$id)->first();
            $oldImage = $banner->getRawOriginal('image') ?? '';
            Storage::delete($oldImage);

            //store new img
            $updatedData['image'] = $request->file('image')->store('banners');

        }
        Banner::where('id',$id)->update($updatedData);
        return redirect()->route('banner')->with(['updated'=>'Banner updated successfully']);
    }

    //delete
    public function destroy($id){
        //delete image
        $banner = Banner::where('id',$id)->first();
        $image = $banner->getRawOriginal('image') ?? '';
        Storage::delete($image);
        //delete banner
        Banner::where('id',$id)->delete();
        return 'success';
    }

    //data table
    public function serverSide(){
        $banner = Banner::query();
        return datatables($banner)
        ->addColumn('image', function ($each) {
            return '<img src="'.$each->image.'" class="thumbnail_img"/>';
        })
        ->addColumn('action', function ($each) {
            $edit_icon = '<a href="'.route('banner.edit', $each->id).'" class="edit_btn mr-3"><i class="ri-edit-box-fill"></i></a>';
            $delete_icon = '<a href="#" class="delete_btn" data-id="'.$each->id.'"><i class="ri-delete-bin-fill"></i></a>';

            return '<div class="action_icon">'.$edit_icon. $delete_icon .'</div>';
        })
        ->rawColumns(['image', 'action'])
        ->toJson();
    }
}