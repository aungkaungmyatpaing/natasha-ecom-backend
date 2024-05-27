@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="{{route('product')}}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Product အချက်အလက်</span>
                </a>
                <a class="primary_button" href="{{ route('product.edit',$product->id) }}">
                    <div class="d-flex align-items-center">
                        <i class="ri-edit-box-fill primary-icon mr-2"></i>
                        <span class="button_content">Product ကို ပြုပြင်မည်</span>
                    </div>
                </a>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-12">
                        <table class="table table-bordered" style="width: 100%">
                            <tbody>
                                <tr>
                                    <th width="30%">အမည်</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th width="30%">အမှတ်တံဆိပ် / Brand</th>
                                    <td>{{ $product->brand->name ?? '---'}}</td>
                                </tr>
                                <tr>
                                    <th width="30%">အမျိူးအစား / Category</th>
                                    <td>{{ $product->category->name ?? '---'}}</td>
                                </tr>
                                <tr>
                                    <th width="30%">စျေးနှုန်း  <div class="{{$product->main_currency_unit == 'USD' ? 'text-danger' : ''}} d-inline-block">( USD )</div></th>
                                    <td>{{ $product->currencies['usd_price'] == 0 ? '---' : $product->currencies['usd_price']}} USD</td>
                                </tr>

                                <tr>
                                    <th width="30%">စျေးနှုန်း  <div class="{{$product->main_currency_unit == 'BAHT' ? 'text-danger' : ''}} d-inline-block">( BAHT )</div></th>
                                    <td>{{ $product->currencies['baht_price'] == 0 ? '---' : $product->currencies['baht_price'] }} BAHT</td>
                                </tr>
                                <tr>
                                    <th width="30%">စျေးနှုန်း  <div class="{{$product->main_currency_unit == 'MMK' ? 'text-danger' : ''}} d-inline-block">( MMK )</div></th>
                                    <td>{{ $product->currencies['mmk_price'] == 0 ? '---' : $product->currencies['mmk_price'] }} MMK</td>
                                </tr>
                                <tr>
                                    <th width="30%">Order လက်ခံမယ့်ငွေကြေးအမျိုးအစား</th>
                                    <td>
                                        @if ($product->active_currency == 'ALL')
                                            USD , BATH , MMK
                                        @else
                                        {{ $product->active_currency}}

                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th width="30%">Stock</th>
                                    <td>
                                        @if ($product->stock == 1)
                                            <div class="badge bg-success">Instock</div>
                                        @else
                                            <div class="badge bg-danger">Out of stock</div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <p class="mb-0">အကြောင်းအရာ / Description</p>
                            </div>
                            <div class="card-body">
                                <p>{{ $product->description}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <p class="mb-0">Images</p>
                            </div>
                            <div class="card-body d-flex flex-wrap">
                                @foreach ($product->images as $img)
                                    <div class="mx-2 rounded">
                                        <img src="{{ $img->path }}" alt="{{ $product->name }}" class="rounded" srcset="" style="width: 100px; height: 100px">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
      $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1800,
                width : '18em',
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
        })
    </script>
@endsection

