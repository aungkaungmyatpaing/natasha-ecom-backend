@extends('main')

@section('content')
<div class="row">

    <div class="col-12">
        <div class="card bg-white border-0">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="{{ URL::previous() }}"
                        class="card-title mb-0 d-inline-flex align-items-center create_title">
                        <i class=" ri-arrow-left-s-line mr-3 primary-icon"></i>
                        <span class="create_sub_title">Order အချက်အလက်</span>
                    </a>
                    @if ($order['status'] == 'pending')
                    <a class="primary_button updateStatusBtn" href="#" data-status="confirm">
                        <div class="d-flex align-items-center">
                            <i class=" ri-checkbox-circle-fill mr-2 primary-icon"></i>
                            <span class="button_content">Order confirm လုပ်မည်</span>
                        </div>
                    </a>
                    @endif
                    @if ($order['status'] == 'confirm')
                    <a class="primary_button updateStatusBtn" href="#" data-status="processing">
                        <div class="d-flex align-items-center">
                            <i class=" ri-checkbox-circle-fill mr-2 primary-icon"></i>
                            <span class="button_content">Order process လုပ်မည်</span>
                        </div>
                    </a>
                    @endif
                    @if ($order['status'] == 'processing')
                    <a class="primary_button updateStatusBtn" href="#" data-status="delivered">
                        <div class="d-flex align-items-center">
                            <i class=" ri-checkbox-circle-fill mr-2 primary-icon"></i>
                            <span class="button_content">Order deliver လုပ်မည်</span>
                        </div>
                    </a>
                    @endif
                    @if ($order['status'] == 'delivered')
                    <a class="primary_button updateStatusBtn" href="#" data-status="complete">
                        <div class="d-flex align-items-center">
                            <i class=" ri-checkbox-circle-fill mr-2 primary-icon"></i>
                            <span class="button_content">Order Complete လုပ်မည်</span>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body ">
                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <div class="mb-0 mr-3">Status :</div>
                        @if ($order['status'] == 'pending')
                        <div class="badge bg-danger  px-3 py-2 rounded-pill">{{ $order['status'] }}</div>

                        @elseif($order['status'] == 'finish')
                        <div class="badge bg-success px-3 py-2 rounded-pill">{{ $order['status'] }}</div>
                        @elseif($order['status'] == 'cancel')
                        <div class="badge bg-dark px-3 py-2 rounded-pill">{{ $order['status'] }}</div>
                        @else
                        <div class="badge bg-info px-3 py-2 rounded-pill">{{ $order['status'] }}</div>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <div class="mb-0 mr-3">Order Time :</div>
                        <div class="">{{ Carbon\Carbon::parse($order['created_at'])->diffForHumans() }}</div>

                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle table-bordered mb-0">
                        <thead class="text-muted text-center" style="background: #F3F6F9">
                            <tr>
                                <th scope="col">Product Image</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Item Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col" class="">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order['order_item'] as $item)
                            <tr class="text-center">
                                <td><img src="{{ $item['product']['image']['path'] }}" class="thumbnail_img" alt=""
                                        srcset=""></td>

                                <td>
                                    {{$item['product']['name'] }}
                                </td>
                                <td>{{ number_format($item['price']) }} Ks</td>
                                <td>{{ $item['quantity'] }}</td>

                                <td class="fw-medium ">
                                    {{ number_format($item['total_price']) }} Ks
                                </td>
                            </tr>
                            @endforeach
                            <tr class="border-top border-top-dashed text-center">
                                <td colspan="3"></td>
                                <td colspan="1" class="fw-bold">Grand Total :</td>

                                <td colspan="1" class="fw-bold">{{ number_format($order['grand_total'],) }} Ks</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card bg-white border-0">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0 text-dark">Delivery အချက်အလက်</h5>
                </div>
            </div>
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th width="20%">Name</th>
                                <td>{{ $order['name'] }}</td>
                            </tr>
                            <tr>
                                <th width="20%">Phone</th>
                                <td>{{ $order['phone'] }}</td>
                            </tr>
                            <tr>
                                <th width="20%">Address</th>
                                <td>{{ $order['address'] }}</td>
                            </tr>
                        </tbody>
                    </table>    
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card bg-white border-0">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0 text-dark">Customer အချက်အလက်</h5>
                </div>
            </div>
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th width="20%">Name</th>
                                <td>{{ $order['customer']['name'] }}</td>
                            </tr>
                            <tr>
                                <th width="20%">Phone</th>
                                <td>{{ $order['customer']['email'] }}</td>
                            </tr>
                            <tr>
                                <th width="20%">Created at</th>
                                <td>{{  Carbon\Carbon::parse($order['customer']['created_at'])->diffForHumans() }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card bg-white border-0">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <h5 class="card-title flex-grow-1 mb-0 text-dark">Payment အချက်အလက်</h5>
                </div>
            </div>
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th width="20%">Payment Method</th>
                                <td>{{ $order['payment_method'] }}</td>
                            </tr>
                            <tr>
                                <th width="20%">Payment Type</th>
                                <td>{{ $order['payment_method'] == 'payment' ? $order['payment']['payment_type'] : 'cod' }}
                                </td>
                            </tr>
                            <tr>
                                <th width="20%">Payment Number</th>
                                <td>{{ $order['payment_method'] == 'payment' ? $order['payment']['number'] : 'cod' }}
                                </td>
                            </tr>
                            <tr>
                                <th width="20%">Payment Photo</th>
                                <td>
                                    @if ($order['payment_method'] == 'payment')
                                    <a href="{{ $order['payment_photo'] }}" class="d-flex flex-column"
                                        data-lightbox="paymentPhoto" data-title="Payment_photo">
                                        <img src="{{ $order['payment_photo'] }}" class="rounded" alt="" srcset=""
                                            style="width: 150px;">
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
@endsection
@section('script')
<script>
$(document).ready(function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 1800,
        width: '18em',
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
            .then((confirm) => {
                if (confirm) {
                    let id = '{{ $order['
                    id '] }}';
                    let status = $('.updateStatusBtn').attr('data-status');
                    console.log(status);
                    $.ajax({
                        url: `/orders/${id}`,
                        method: 'POST',
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
