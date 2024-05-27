<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAdsRequest;
use App\Http\Requests\UpdateAdsRequest;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    //index
    public function index(){
        return view('backend.ads.index');
    }

    //create
    public function create(){
        return view('backend.ads.create');
    }

    //store
    public function store(StoreAdsRequest $request){
        // $ads = new Ads();
        $data = [
            'slider_duration' => $request->slider_duration,
            'status' => $request->status,
        ];
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('ads');
        }
        Ads::create($data);
        return redirect()->route('ads')->with('created', 'Ads created Successfully');
    }

     //edit
     public function edit($id){
        $ads = Ads::where('id',$id)->first();
        return view('backend.ads.edit')->with(['ads'=>$ads]);
    }

    //update
    public function update(UpdateAdsRequest $request,$id){
        $updatedData = [
            'status' => $request->status,
            'slider_duration' => $request->slider_duration,
        ];
        if($request->hasFile('image')){
            //delete old img
            $ads = Ads::where('id',$id)->first();
            $oldImage = $ads->getRawOriginal('image') ?? '';
            Storage::delete($oldImage);

            //store new img
            $updatedData['image'] = $request->file('image')->store('ads');
        }
        Ads::where('id',$id)->update($updatedData);
        return redirect()->route('ads')->with(['updated'=>'Ads updated successfully']);
    }

     //delete
     public function destroy($id){
        //delete image
        $ads = Ads::where('id',$id)->first();
        $image = $ads->getRawOriginal('image') ?? '';
        Storage::delete($image);
        $ads->delete();
        return 'success';
    }

    //server side
    public function serverSide()
    {
        $ads = Ads::query();
        return datatables($ads)
        ->addColumn('image', function ($each) {
            return '<img src="'.$each->image.'" class="thumbnail_img"/>';
        })
        ->editColumn('slider_duration',function($each){
            return $each->slider_duration.' second';
        })
        ->editColumn('status',function($each){
            return $each->status == 1 ? '<div class="badge bg-success">active</div>' : '<div class="badge bg-danger">inactive</div>';
        })
        ->addColumn('action', function ($each) {
            $edit_icon = '<a href="'.route('ads.edit', $each->id).'" class="edit_btn mr-3"><i class="ri-edit-box-fill"></i></a>';
            $delete_icon = '<a href="#" class="delete_btn" data-id="'.$each->id.'"><i class="ri-delete-bin-fill"></i></a>';

            return '<div class="action_icon">'.$edit_icon. $delete_icon .'</div>';
        })
        ->rawColumns(['image','slider_duration','status','action'])
        ->toJson();
    }
}