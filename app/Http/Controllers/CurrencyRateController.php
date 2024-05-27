<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCurrencyRequest;

class CurrencyRateController extends Controller
{
    //index
    public function index(){
        return view('backend.currency.index');
    }

    //create
    public function create(){
        return view('backend.currency.create');
    }

    //store
    public function store(StoreCurrencyRequest $request){
        $currency_count = CurrencyRate::count();

        if($currency_count == 6 || $currency_count > 6) {
            return redirect()->route('currency')->with(['infoMessage'=>'Invalid.']);
        }

        //from - to
        if(CurrencyRate::where('from_currency_unit',$request->from_currency_unit)->where('currency_unit',$request->currency_unit)->exists()){
            return back()->with(['infoMessage'=> $request->from_currency_unit.' to '.$request->currency_unit.' အတွက် Currency Rate ထည့်ပြီးသားဖြစ်နေသောကြောင့် ထပ်မံထည့်မရနိုင်ပါ။']);
        }
        CurrencyRate::create([
            'from_currency_unit' => $request->from_currency_unit,
            'currency_rate' => $request->currency_rate,
            'currency_unit' => $request->currency_unit,
        ]);

        return redirect()->route('currency')->with(['created'=>'Currency Rate added successfully']);
    }

    public function edit($id)
    {
        $currency = CurrencyRate::findOrFail($id);
        return view('backend.currency.edit', compact('currency'));
    }

    public function update(StoreCurrencyRequest $request,$id){
        if(CurrencyRate::where('id','!=',$id)->where('from_currency_unit',$request->from_currency_unit)->where('currency_unit',$request->currency_unit)->exists()){
            return back()->with(['infoMessage'=> $request->from_currency_unit.' to '.$request->currency_unit.' အတွက် Currency Rate ထည့်ပြီးသားဖြစ်နေသောကြောင့် ထပ်မံထည့်မရနိုင်ပါ။']);
        }
        
        CurrencyRate::where('id',$id)->update([
            'from_currency_unit' => $request->from_currency_unit,
            'currency_rate' => $request->currency_rate,
            'currency_unit' => $request->currency_unit,
        ]);

        return redirect()->route('currency')->with('updated', 'Currency Rate updated Successfully');
    }

    public function destroy($id)
    {
        $currency = CurrencyRate::findOrFail($id);
        $currency->delete();
        return 'success';
    }

    //datatables
    public function serverSide()
    {
        $currencies = CurrencyRate::query();
        return datatables($currencies)
        ->addColumn('from_currency_unit',function($each){
            return '<div>1 '.$each->from_currency_unit.'</div>';
        })
        ->addColumn('currency_rate',function($each){
            if(is_float($each->currency_rate + 0)){
                return '<div>'.number_format($each->currency_rate,4).' '.'<div class="fw-bold d-inline-block">'.$each->currency_unit.'</div></div>';
            }
            return '<div>'.$each->currency_rate.' '.'<div class="fw-bold d-inline-block">'.$each->currency_unit.'</div></div>';
        })
        ->addColumn('action', function ($each) {
            $edit_icon = '<a href="'.route('currency.edit', $each->id).'" class="edit_btn mr-3"><i class="ri-edit-box-fill"></i></a>';
            $delete_icon = '<a href="#" class="delete_btn" data-id="'.$each->id.'"><i class="ri-delete-bin-fill"></i></a>';

            return '<div class="action_icon">'.$edit_icon. $delete_icon .'</div>';
        })
        ->rawColumns(['from_currency_unit','currency_rate','action'])
        ->toJson();
    }
}