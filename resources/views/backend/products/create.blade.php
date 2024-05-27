@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card">
            <div class="card-header">
                <a href="{{route('product')}}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Product အသစ်ပြုလုပ်မည်</span>
                </a>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-9">
                        <form method="POST" action="{{route('product.store')}}" id="product_create" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-xl-6">

                                    <div class="mb-3">
                                        <label class="form-label mb-3">အမည်</label>
                                        <input type="text" class="form-control" name="name" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-3">စျေးနှုန်း</label>
                                        <input type="number" class="form-control" name="price" autocomplete="off">
                                    </div>

                                </div>
                                <div class="col-xl-6">
                                    <div class="">
                                        <label for="" class="form-label mb-3">ငွေကြေးအမျိူးအစား</label>
                                        <select name="main_currency_unit" id="" class="form-control">
                                            <option value="USD">USD</option>
                                            <option value="BAHT">BAHT</option>
                                            <option value="MMK">MMK</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="category">အမျိူးအစား / Category</label>
                                        <select name="category_id" class="form-select mb-3" aria-label="Default select example" id='category'>
                                            <option selected disabled>အမျိူးအစား ရွေးပါ</option>
                                            @foreach ($categories as $category)
                                                <option value="{{$category->id}}">
                                                    {{$category->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label for="brand">အမှတ်တံဆိပ် / Brand</label>
                                        <select name="brand_id" class="form-select mb-3" aria-label="Default select example" id='brand'>
                                            <option selected disabled>အမှတ်တံဆိပ် ရွေးပါ</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{$brand->id}}">
                                                    {{$brand->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <label for="" class="mt-4">Order လက်ခံမယ့်ငွေကြေးအမျိုးအစား</label>
                            <div class="mb-5 mt-3 px-3 d-flex gap-5">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="active_currency" id="active_currency_4" value="ALL" checked>
                                    <label class="form-check-label" for="active_currency_3">
                                      USD, BAHT , MMK
                                    </label>
                                  </div>
                                  <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="active_currency" id="active_currency_1" value="USD">
                                    <label class="form-check-label" for="active_currency_1">
                                      USD သာရမည်
                                    </label>
                                  </div>
                                  <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="active_currency" id="active_currency_2" value="BAHT">
                                    <label class="form-check-label" for="active_currency_2">
                                      BAHT သာရမည်
                                    </label>
                                  </div>
                                  <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="active_currency" id="active_currency_3" value="MMK">
                                    <label class="form-check-label" for="active_currency_3">
                                      MMK သာရမည်
                                    </label>
                                  </div>
                            </div>

                            <div class="mb-5 mt-3">
                                <label for="description" class="form-label">အကြောင်းအရာ / Description</label>
                                <textarea class="form-control" name="description" id="description" rows="8"></textarea>
                            </div>


                            <div class="form-group mb-3">
                                <label for="images">Images</label>
                                <div class="input-images" id="images"></div>
                            </div>
                            <div class="mt-4">
                                <div class="form-check  mb-3">
                                    <input class="form-check-input" type="radio" name="stock" id="inStockRadio" value="1" checked>
                                    <label class="form-check-label" for="inStockRadio">
                                      In Stock ရှိသည်
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="stock" id="outStockRadio" value="0">
                                    <label class="form-check-label" for="outStockRadio">
                                      Out of Stock ဖြစ်နေသည်
                                    </label>
                                  </div>
                            </div>
                            <div class="text-end submit-m-btn">
                                <button type="submit" class="submit-btn">အသစ်ပြုလုပ်မည်</button>
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreProductRequest', '#product_create') !!}
    <script src="{{ asset('assets/js/image-uploader.min.js') }}"></script>
    <script>
        $(".input-images").imageUploader({
            maxSize: 2 * 1024 * 1024,
            maxFiles: 10,
        });
    </script>
@endsection
