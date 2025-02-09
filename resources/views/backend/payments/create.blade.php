@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card">
            <div class="card-header">
                <a href="{{route('payment')}}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Payment အသစ်ဖန်တီးမည်</span>
                </a>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-9">
                        <form method="POST" action="{{route('payment.store')}}" enctype="multipart/form-data" id="payment_create">
                            @csrf
                            <div class="upload mb-3">
                                <div class="preview_img">
                                    <img src="{{asset('images/default.jpg')}}" width=150 height=150 alt="">
                                </div>
                                <div class="round">
                                  <input type="file" id="upload_img" name="image">
                                  <i class ="ri-camera-fill" style = "color: #fff;"></i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="employeeName" class="form-label mb-3">အမျိုးအစား / Payment Type</label>
                                <input type="text" class="form-control" name="payment_type" placeholder="Eg. KPay">
                            </div>
                            <div class="mb-3">
                                <label for="employeeName" class="form-label mb-3">နာမည်</label>
                                <input type="text" class="form-control" name="name" placeholder="">
                            </div>
                            <div class="mb-3">
                                <label for="employeeName" class="form-label mb-3">Payment နံပါတ်</label>
                                <input type="text" class="form-control" name="number">
                            </div>
                             <div class="mb-3">
                                <label for="employeeName" class="form-label mb-3">ငွေပေးချေနိုင်သော ငွေကြေးအမျိုးအစား</label>
                                <div class="">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="available_currency[]" type="checkbox" id="inlineCheckbox1" value="USD">
                                        <label class="form-check-label" for="inlineCheckbox1">USD</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="available_currency[]" type="checkbox" id="inlineCheckbox2" value="BAHT">
                                        <label class="form-check-label" for="inlineCheckbox2">BAHT</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="available_currency[]" type="checkbox" id="inlineCheckbox3" value="MMK" checked>
                                        <label class="form-check-label" for="inlineCheckbox3">MMK</label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end submit-m-btn">
                                <button type="submit" class="submit-btn">အသစ်ပြုလုပ်မည့် Payment</button>
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
    {!! JsValidator::formRequest('App\Http\Requests\StorePaymentRequest', '#payment_create') !!}

    <script>
         $(document).ready(function() {
             $('#upload_img').on('change', function() {
                 let file_length = document.getElementById('upload_img').files.length;
                 if(file_length > 0) {
                     $('.preview_img').html('');
                     for(i = 0; i < file_length ; i++) {
                         $('.preview_img').html('');
                         $('.preview_img').append(`<img src="${URL.createObjectURL(event.target.files[i])}" width=150 height =150/>`)
                     }
                 } else {
                     $('.preview_img').html(`<img src="{{asset('images/default.jpg')}}" width=150 height=150 alt="">`);
                 }
             })
         })
    </script>
@endsection
