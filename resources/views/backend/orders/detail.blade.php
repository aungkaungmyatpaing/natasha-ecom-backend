@extends('main')

@section('content')
<div class="row">
    <div class="col-xl-12 ">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <a href="{{ URL::previous() }}" class="card-title mb-0 d-inline-flex align-items-center create_title">
                    <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                    <span class="create_sub_title">Order အချက်အလက်</span>
                </a>
                @if ($order->status == 'pending')
                    <a class="primary_button updateStatusBtn" href="#" data-status="confirm">
                        <div class="d-flex align-items-center">
                            <i class=" ri-checkbox-circle-fill mr-2 primary-icon"></i>
                        <span class="button_content">Order confirm လုပ်မည်</span>
                        </div>
                    </a>
                @endif
                @if ($order->status == 'confirm')
                    <a class="primary_button updateStatusBtn" href="#" data-status="processing">
                        <div class="d-flex align-items-center">
                            <i class=" ri-checkbox-circle-fill mr-2 primary-icon"></i>
                        <span class="button_content">Order process လုပ်မည်</span>
                        </div>
                    </a>
                @endif
                @if ($order->status == 'processing')
                    <a class="primary_button updateStatusBtn" href="#" data-status="delivered">
                        <div class="d-flex align-items-center">
                            <i class=" ri-checkbox-circle-fill mr-2 primary-icon"></i>
                        <span class="button_content">Order deliver လုပ်မည်</span>
                        </div>
                    </a>
                @endif
                @if ($order->status == 'delivered')
                    <a class="primary_button updateStatusBtn" href="#" data-status="finish">
                        <div class="d-flex align-items-center">
                            <i class=" ri-checkbox-circle-fill mr-2 primary-icon"></i>
                        <span class="button_content">Order finish လုပ်မည်</span>
                        </div>
                    </a>
                @endif
            </div><!-- end card header -->
            <div class="card-body">

                <div class="row mb-4">
                    <div class="col-6">
                        <div class="mb-2 d-flex align-items-center">
                            <div class="mb mr-3">Status :</div>
                            @if ($order->status == 'pending')
                                <div class="badge bg-danger  px-3 py-2 rounded-pill">{{ $order->status }}</div>

                            @elseif($order->status == 'finish')
                                <div class="badge bg-success px-3 py-2 rounded-pill">{{ $order->status }}</div>
                            @elseif($order->status == 'cancel')
                                <div class="badge bg-dark px-3 py-2 rounded-pill">{{ $order->status }}</div>
                            @else
                                <div class="badge bg-info px-3 py-2 rounded-pill">{{ $order->status }}</div>
                            @endif
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            @if ($order->preorder_date)
                                <div class="mb mr-3">Preorder :</div>
                                <div class=""><div class="badge bg-success rounded-circle text-center"><i class="ri-check-line my-0" style="font-size: 15px;"></i></div></div>
                            @endif
                        </div>
                        <div class="mb-2 d-flex align-items-center">
                            @if ($order->preorder_date)
                                <div class="mb mr-3">Preorder Date :</div>
                                <div class="">{{ $order->changeDateFormat(Carbon\Carbon::parse($order->preorder_date)) }}</div>
                            @else
                                <div class="mb mr-3">Order Date :</div>
                                {{-- <div class="">{{$order->changeDateFormat($order->created_at)}}</div> --}}
                                <div class="">{{$order->changeDateFormat($order->created_at)}}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <table class="table table-bordered" style="width: 100%">
                            <tbody>
                                {{-- <tr>
                                    <th width="40%">Order Products</th>
                                    <td>{{ count($order->orderItem) }} Products</td>
                                </tr> --}}
                                {{-- @if ($order['currency_rate'])
                                <!-- <tr>
                                    <th width="40%">Currency Rate
                                        <div class="">{{$order->changeDateFormat($order->created_at)}}</div>
                                    </th>
                                    <td>{{ $order['currency_rate'] ? '1 USD => '.$order['currency_rate'] : ''}}</td>
                                </tr> -->
                                @endif --}}
                                 @if ($order->orderCurrencyRate)
                                <tr>
                                    <th width="40%">
                                        {{$order->changeDateFormat($order->created_at)}}
                                        <div class="">ကငွေလဲလှယ်နှုန်းထားများ</div>
                                        <hr>
                                        Currency Rates (History) of 
                                        <div class="">{{$order->changeDateFormat($order->created_at)}}</div>
                                    </th>
                                    <td>
                                       <div class="">
                                            @foreach($currency_histories as $currency_history)
                                                <div class="">1 {{ $currency_history->from_currency_unit }} = {{ $currency_history->currency_rate }} {{ $currency_history->currency_unit }}</div>
                                            @endforeach
                                       </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row d-flex justify-content-center mb-4">
                    <div class="col-xl-12">
                        <table class="table table-bordered" style="width:100%;">
                            <thead class="text-center">
                                <th class="text-center">Image</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Price </th>
                                <th class="text-center">Total Price</th>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItem as $item)
                                <tr class="text-center">
                                    <td><img src="{{ $item->product->image->path }}" class="thumbnail_img"  alt="" srcset=""></td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        @if($order->user_selected_currency == 'USD')
                                            {{ $item->usd_price }}  {{ $order->user_selected_currency }}
                                        @elseif($order->user_selected_currency == 'BAHT')
                                            {{ $item->baht_price }}  {{ $order->user_selected_currency }}
                                        @else 
                                            {{ $item->mmk_price }}  {{ $order->user_selected_currency }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->user_selected_currency == 'USD')
                                            {{ $item->usd_total_price }}  {{ $order->user_selected_currency }}
                                        @elseif($order->user_selected_currency == 'BAHT')
                                            {{ $item->baht_total_price }}  {{ $order->user_selected_currency }}
                                        @else 
                                            {{ $item->mmk_total_price }}  {{ $order->user_selected_currency }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="text-end " colspan="4">Grand Total </td>
                                    <td class="text-center " colspan="1">
                                        <div class="fw-bold ml-1 d-inline-block">
                                            @if($order->user_selected_currency == 'USD')
                                                {{ $order->usd_grand_total }} {{ $order->user_selected_currency }}
                                            @elseif($order->user_selected_currency == 'BAHT')
                                                {{ $order->baht_grand_total }} {{ $order->user_selected_currency }}
                                            @else 
                                                {{ $order->mmk_grand_total }} {{ $order->user_selected_currency }}
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12">

                        <p>Grand Total</p>
                        <table class="table table-bordered" style="width: 100%">
                            <tbody>
                                <tr>
                                    <th style="width: 20%">Grand Total ( USD )</th>
                                    <td>
                                        <div class="ml-1 d-inline-block">{{ $order->usd_grand_total }} USD</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 20%">Grand Total ( BAHT )</th>
                                    <td>
                                        <div class="ml-1 d-inline-block">{{ $order->baht_grand_total }} BAHT</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 20%">Grand Total ( MMK )</th>
                                    <td>
                                        <div class="ml-1 d-inline-block">{{ $order->mmk_grand_total }} MMK</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row d-flex justify-content-center mb-4">

                    <div class="col-xl-6">
                        <p>Delivery အချက်အလက်</p>
                        <table class="table table-bordered" style="width: 100%">
                            <tbody>
                                <tr>
                                    <th width="40%">Name</th>
                                    <td>{{ $order->name }}</td>
                                </tr>
                                <tr>
                                    <th width="40%">Phone</th>
                                    <td>{{ $order->phone }}</td>
                                </tr>
                                <tr>
                                    <th width="40%">Address</th>
                                    <td>{{ $order->address }}</td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                    <div class="col-xl-6">
                        <p>Customer အချက်အလက်</p>
                        <table class="table table-bordered" style="width: 100%">
                            <tbody>
                                {{-- <tr>
                                    <th width="40%">Order Products</th>
                                    <td>{{ count($order->orderItem) }} Products</td>
                                </tr> --}}
                                {{-- @if ($order['currency_rate'])
                                <tr>
                                    <th width="40%">Currency Rate
                                        <div class="">{{$order->changeDateFormat($order->created_at)}}</div>
                                    </th>
                                    <td>{{ $order['currency_rate'] ? '1 USD => '.$order['currency_rate'] : ''}}</td>
                                </tr>
                                @endif --}}
                                <tr>
                                    <th width="40%">Name</th>
                                    <td>{{ $order->customer->name }}</td>
                                </tr>
                                <tr>
                                    <th width="40%">Email</th>
                                    <td>{{ $order->customer->email }}</td>
                                </tr>
                                <tr>
                                    <th width="40%">Created At</th>
                                    <td>{{$order->changeDateFormat($order->created_at)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <p>Payment အချက်အလက်</p>
                        <table class="table table-bordered" style="width: 100%">
                            <tbody>
                                <tr>
                                    <th style="width: 20%">Payment Method</th>
                                    <td>{{ $order->payment_method }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 20%">Payment Type</th>
                                    <td>{{ $order->payment_method == 'payment' ? $order->payment->payment_type : 'cod' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 20%">Payment Number</th>
                                    <td>{{ $order->payment_method == 'payment' ? $order->payment->number : 'cod' }}</td>
                                </tr>
                                <tr>
                                    <th style="width: 20%">Payment Photo</th>
                                    <td>
                                        @if ($order->payment_method == 'payment')
                                            <a href="{{ $order->payment_photo }}" class="d-flex flex-column" data-lightbox="paymentPhoto" data-title="Payment_photo">
                                                <img src="{{ $order->payment_photo }}" class="rounded"  alt="" srcset="" style="width: 150px;">
                                            </a>
                                        @else
                                            <div class="">---</div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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

            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true
            })

            $(document).on('click', '.updateStatusBtn', function(e) {
              e.preventDefault();
              swal({
                text: "Are you sure?",
                icon: "info",
                buttons: true,
                dangerMode: true,
              })
              .then((willDelete) => {
                if (willDelete) {
                  let id = '{{ $order->id }}';
                  let status = $('.updateStatusBtn').attr('data-status');
                  console.log(status);
                  $.ajax({
                    url : `/orders/${id}`,
                    method : 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        'status': status,
                    },
                  }).done(function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Order updated successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                  })
                }
              });
            })
        })
    </script>
@endsection

