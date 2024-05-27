@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card">
            <div class="card-header">
                <a href="{{route('currency')}}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Curreny Rate အသစ်ထည့်မည်</span>
                </a>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-9">
                        <form method="POST" action="{{route('currency.store')}}" id="brand_create">
                            @csrf

                            <div class="d-flex w-100">
                                <div class="mb-3" style="width: 60%">
                                    <label for="employeeName" class="form-label mb-3">From </label>
                                    <input type="number"  class="form-control" name="" value="1" disabled>
                                </div>
                                <div class="mb-3 ml-3" style="width: 40%">
                                    <label for="" class="form-label mb-3">ငွေကြေးအမျိုးအစား</label>
                                    <select name="from_currency_unit" id="from_currency_unit" class="form-control">
                                        <option value="USD">USD</option>
                                        <option value="MMK">MMK</option>
                                        <option value="BAHT">BAHT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex w-100">
                                <div class="mb-3" style="width: 60%">
                                    <label for="employeeName" class="form-label mb-3">To</label>
                                    <input type="number" class="form-control" name="currency_rate"  id="currency_rate">
                                </div>
                                <div class="mb-3 ml-3" style="width: 40%">
                                    <label for="" class="form-label mb-3">ငွေကြေးအမျိုးအစား</label>
                                    <select name="currency_unit" id="currency_unit" class="form-control">
                                        <option value="USD">USD</option>
                                        <option value="MMK">MMK</option>
                                        <option value="BAHT">BAHT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-end submit-m-btn">
                                <button type="submit" class="submit-btn">Curreny Rate အသစ်ပြုလုပ်မည်</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    {!! JsValidator::formRequest('App\Http\Requests\StoreCurrencyRequest', '#brand_create') !!}

    <script>

    </script>
@endsection
