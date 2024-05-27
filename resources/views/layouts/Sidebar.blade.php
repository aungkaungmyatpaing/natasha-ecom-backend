<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box mb-4 mt-2">
        <!-- Dark Logo-->
        <a href="" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('/images/default.jpg') }}" alt="" class="rounded-circle mt-3" height="100">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('/images/default.jpg') }}" alt="" class="rounded-circle mt-3" height="100">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('/images/default.jpg') }}" alt="" class="rounded-circle mt-3" height="100">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('/images/default.jpg') }}" alt="" class="rounded-circle mt-3" height="100">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarApps" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-apps-2-line"></i> <span data-key="t-apps">Apps</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarApps">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="apps-calendar.html" class="nav-link" data-key="t-calendar"> Calendar </a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarEcommerce" class="nav-link" data-bs-toggle="collapse" role="button"
                                    aria-expanded="false" aria-controls="sidebarEcommerce" data-key="t-ecommerce">
                                    Ecommerce
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarEcommerce">
                                    <ul class="nav nav-sm flex-column">
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-products.html" class="nav-link"
                                                data-key="t-products"> Products </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-product-details.html" class="nav-link"
                                                data-key="t-product-Details"> Product Details </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-add-product.html" class="nav-link"
                                                data-key="t-create-product"> Create Product </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-orders.html" class="nav-link" data-key="t-orders">
                                                Orders </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-order-details.html" class="nav-link"
                                                data-key="t-order-details"> Order Details </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-customers.html" class="nav-link"
                                                data-key="t-customers"> Customers </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-cart.html" class="nav-link"
                                                data-key="t-shopping-cart"> Shopping Cart </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-checkout.html" class="nav-link"
                                                data-key="t-checkout"> Checkout </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-sellers.html" class="nav-link" data-key="t-sellers">
                                                Sellers </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="apps-ecommerce-seller-details.html" class="nav-link"
                                                data-key="t-sellers-details"> Seller Details </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li> --}}

                {{-- <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span></li> --}}

                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('dashboard') ? 'active' : ''}}" href="{{route('dashboard')}}">
                        <i class="ri-dashboard-line"></i> <span data-key="t-landing">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('currency') ? 'active' : ''}}" href="{{route('currency')}}">
                        <i class="ri-money-dollar-box-fill"></i><span data-key="t-landing">Currency Rate</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('customer') ? 'active' : ''}}" href="{{route('customer')}}">
                        <i class="ri-user-3-fill"></i><span data-key="t-landing">Customers</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('ads') ? 'active' : ''}}" href="{{route('ads')}}">
                        <i class="ri-advertisement-fill"></i> <span data-key="t-landing">Ads</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('brand') ? 'active' : ''}}" href="{{route('brand')}}">
                        <i class="ri-gift-fill"></i> <span data-key="t-landing">Brands</span>
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link menu-link {{ request()->url() == route('category') ? 'active' : ''}}" href="{{route('category')}}">
                        <i class="ri-gift-fill"></i> <span data-key="t-landing">Categories</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('product') ? 'active' : ''}}" href="{{route('product')}}">
                        <i class="ri-product-hunt-fill"></i> <span data-key="t-landing">Products</span>
                    </a>
                </li>





                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('banner') ? 'active' : ''}}" href="{{route('banner')}}">
                        <i class="ri-slideshow-4-fill"></i> <span data-key="t-landing">Banners</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('payment') ? 'active' : ''}}" href="{{route('payment')}}">
                        <i class="ri-bank-card-2-fill"></i> <span data-key="t-landing">Payments</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('preorder') ? 'active' : ''}}" href="{{route('preorder')}}">
                        <i class="ri-shopping-cart-2-fill"></i> <span data-key="t-landing">Preorders</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link  {{ request()->url() == route('order.pending') ? 'active' : ''}}" href="{{route('order.pending')}}">
                        <i class="ri-shopping-cart-2-fill"></i> <span data-key="t-landing">Pending Orders</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->url() == route('order') ? 'active' : ''}}" href="{{route('order')}}">
                        <i class="ri-shopping-cart-2-fill"></i> <span data-key="t-landing">Order Histories</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
