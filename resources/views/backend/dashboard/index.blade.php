@extends('main')

@section('content')
   <div class="row">
        <div class="col-3">
            <a href="{{ route('order') }}" class="card dashboardCard">
                <div class="card-body d-flex align-items-center">
                    <i class="ri-shopping-cart-2-fill"></i>
                    <div class="ps-2 ps-lg-3 ps-xl-3">
                        <h5 class="mb-1">{{ App\Models\Order::count() }}</h5>
                        <span class="">Total Orders</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-3">
        <a href="{{ route('order.pending') }}" class="card dashboardCard">
                <div class="card-body d-flex align-items-center">
                    <i class="ri-shopping-cart-2-fill"></i>
                    <div class="ps-2 ps-lg-3 ps-xl-3">
                        <h5 class="mb-1">{{ App\Models\Order::where('status','pending')->count() }}</h5>
                        <span class="">Pending Orders</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-3">
            <a href="{{ route('product') }}" class="card dashboardCard">
                <div class="card-body d-flex align-items-center">
                    <i class="ri-product-hunt-fill"></i>
                    <div class="ps-2 ps-lg-3 ps-xl-3">
                        <h5 class="mb-1">{{ App\Models\Product::count() }}</h5>
                        <span class="">Total Products</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-3">
            <a href="{{ route('customer') }}" class="card dashboardCard">
                <div class="card-body d-flex align-items-center">
                    <i class="ri-user-3-fill"></i>
                    <div class="ps-2 ps-lg-3 ps-xl-3">
                        <h5 class="mb-1">{{ App\Models\Customer::count() }}</h5>
                        <span class="">Total Customers</span>
                    </div>
                </div>
            </a>
        </div>

   </div>
   <div class="row">
    <div class="col-6">
        <div class="card order">
            <div class="card-header d-flex justify-content-between py-3">
                <div class="d-flex align-items-center">
                    <span class="button_content">Top Products</span>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="width:100%;">
                        <thead class="text-center">
                            <th class="text-center">Image</th>
                            <th class="text-center">နာမည်</th>
                            <th class="text-center">Total Orders</th>
                        </thead>
                        <tbody>
                            @if ($topProducts->count() == 0)
                                <tr class="text-center">
                                    <td colspan="3" class="text-secondary">No data available in table</td>
                                </tr>
                            @else
                                @foreach ($topProducts as $item)
                                <tr class="text-center">
                                    <td>
                                        <img src="{{ $item->images->first()->path }}" class="thumbnail_img" alt="" srcset="">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->total_sales,) }} <div class="fw-bold ml-1 d-inline-block"></div></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card order">
            <div class="card-header d-flex justify-content-between py-3">
                <div class="d-flex align-items-center">
                    <span class="button_content">Top Customers</span>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="width:100%;">
                        <thead class="text-center">
                            <th class="text-center">နာမည်</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Total Orders</th>
                        </thead>
                        <tbody>
                            @if ($topCustomers->count() == 0)
                                <tr class="text-center">
                                    <td colspan="3" class="text-secondary">No data available in table</td>
                                </tr>
                            @else
                                @foreach ($topCustomers as $item)
                                <tr class="text-center">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ number_format($item->total_sales,) }} <div class="fw-bold ml-1 d-inline-block"></div></td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   </div>
@endsection
