@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card">
            <div class="card-header">
                <a href="{{route('product')}}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Product ကိုပြုပြင်မည်</span>
                </a>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-9">
                        @if(Session::get('fail'))
                            <div class="alert alert-danger p-3 mb-3 text-center">
                                {{Session::get('fail')}}
                            </div>
                        @endif
                        <form method="POST" action="{{route('product.update', $product->id)}}" id="product_update" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-3">အမည်</label>
                                        <input type="text" class="form-control" name="name" autocomplete="off" value="{{$product->name}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="mb-3">
                                        <label class="form-label mb-3">စျေးနှုန်း</label>
                                        @if ($product->main_currency_unit == 'USD')
                                            <input type="number" class="form-control" name="price" value="{{  $product->price }}" autocomplete="off">
                                        @elseif ($product->main_currency_unit == 'BAHT')
                                            <input type="number" class="form-control" name="price" value="{{  $product->price }}" autocomplete="off">
                                        @else
                                            <input type="number" class="form-control" name="price" value="{{  $product->price }}" autocomplete="off">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="">
                                        <label for="" class="form-label mb-3">ငွေကြေးအမျိူးအစား</label>
                                        <select name="main_currency_unit" id="" class="form-control">
                                            <option value="USD" {{$product->main_currency_unit == 'USD' ? 'selected' : ''}}>USD</option>
                                            <option value="BAHT" {{$product->main_currency_unit == 'BAHT' ? 'selected' : ''}}>BAHT</option>
                                            <option value="MMK" {{$product->main_currency_unit == 'MMK' ? 'selected' : ''}}>MMK</option>
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
                                                <option value="{{$category->id}}" {{$category->id == $product->category_id ? 'selected' : ''}}>
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
                                                <option value="{{$brand->id}}" {{$brand->id == $product->brand_id ? 'selected' : ''}}>
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
                                    <input class="form-check-input" type="radio" name="active_currency" id="active_currency_4" value="ALL" {{$product->active_currency == "ALL" ? 'checked' : ''}}>
                                    <label class="form-check-label" for="active_currency_3">
                                      USD, BAHT , MMK
                                    </label>
                                  </div>
                                  <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="active_currency" id="active_currency_1" value="USD" {{$product->active_currency == "USD" ? 'checked' : ''}}>
                                    <label class="form-check-label" for="active_currency_1">
                                      USD သာရမည်
                                    </label>
                                  </div>
                                  <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="active_currency" id="active_currency_2" value="BAHT" {{$product->active_currency == "BAHT" ? 'checked' : ''}}>
                                    <label class="form-check-label" for="active_currency_2">
                                      BAHT သာရမည်
                                    </label>    
                                  </div>
                                  <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="active_currency" id="active_currency_3" value="MMK" {{$product->active_currency == "MMK" ? 'checked' : ''}}>
                                    <label class="form-check-label" for="active_currency_3">
                                      MMK သာရမည်
                                    </label>
                                  </div>
                            </div>

                            <div class="mb-5 mt-3">
                                <label for="description" class="form-label">အကြောင်းအရာ / Description</label>
                                <textarea class="form-control" name="description" id="description" rows="8">{{$product->description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="images">Images</label>
                                <div class="input-images" id="images"></div>
                            </div>
                            <div class="mt-4">
                                <div class="form-check  mb-3">
                                    <input class="form-check-input" type="radio" name="stock" id="inStockRadio" value="1" {{ $product->stock == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inStockRadio">
                                      In Stock ရှိသည်
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="stock" id="outStockRadio" value="0" {{ $product->stock == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="outStockRadio">
                                      Out of Stock ဖြစ်နေသည်
                                    </label>
                                  </div>
                            </div>
                            <div class="text-end submit-m-btn">
                                <button type="submit" class="submit-btn">ပြင်ဆင်မှုများကိုသိမ်းမည်</button>
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
    {!! JsValidator::formRequest('App\Http\Requests\UpdateProductRequest', '#product_update') !!}
    <script src="{{ asset('assets/js/image-uploader.min.js') }}"></script>
    <script>
        $.ajax({
            url: `/product-images/${`{{ $product->id }}`}`
            }).done(function(response) {
            if( response ){
                $('.input-images').imageUploader({
                    preloaded: response,
                    imagesInputName: 'images',
                    preloadedInputName: 'old',
                    maxSize: 2 * 1024 * 1024,
                    maxFiles: 10
                });
            }
        });
    </script>
@endsection
