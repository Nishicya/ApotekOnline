<div id="header">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- LOGO -->
            <div class="col-md-3">
                <div class="header-logo">
                    <a href="#" class="logo">
                        <img src="{{ asset('fe/img/logo.png') }}" alt="">
                    </a>
                </div>
            </div>
            <!-- /LOGO -->

            <!-- SEARCH BAR -->
            <div class="col-md-6">
                <div class="header-search">
                    <form>
                        <select class="input-select">
                            <option value="0">All Categories</option>
                            <option value="1">Category 01</option>
                            <option value="1">Category 02</option>
                        </select>
                        <input class="input" placeholder="Search here">
                        <button class="search-btn">Search</button>
                    </form>
                </div>
            </div>
            <!-- /SEARCH BAR -->

            <!-- ACCOUNT -->
            <div class="col-md-3 clearfix">
                <div class="header-ctn">
                    <!-- Cart -->
                    <div class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Your Cart</span>
                            <div class="qty">3</div>
                        </a>
                        <div class="cart-dropdown">
                            <div class="cart-list">
                                <div class="product-widget">
                                    <div class="product-img">
                                        <img src="{{ asset('fe/img/product01.png') }}" alt="">
                                    </div>
                                    <div class="product-body">
                                        <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                        <h4 class="product-price"><span class="qty">1x</span>$980.00</h4>
                                    </div>
                                    <button class="delete"><i class="fa fa-close"></i></button>
                                </div>

                                <div class="product-widget">
                                    <div class="product-img">
                                        <img src="{{ asset('fe/img/product02.png') }}" alt="">
                                    </div>
                                    <div class="product-body">
                                        <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                        <h4 class="product-price"><span class="qty">3x</span>$980.00</h4>
                                    </div>
                                    <button class="delete"><i class="fa fa-close"></i></button>
                                </div>
                            </div>
                            <div class="cart-summary">
                                <small>3 Item(s) selected</small>
                                <h5>SUBTOTAL: $2940.00</h5>
                            </div>
                            <div class="cart-btns">
                                <a href="#">View Cart</a>
                                <a href="#">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /Cart -->

                     <!-- Profile -->
                    <div class="dropdown" style="display: flex; align-items: center;">
                        @if(session('loginId'))
                            <?php
                                $pelanggan = \App\Models\Pelanggan::find(session('loginId'));
                            ?>

                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" style="display: flex; align-items: center;">
                                @if($pelanggan && $pelanggan->foto)
                                    <img src="{{ asset('storage/'.$pelanggan->foto) }}" 
                                        class="rounded-circle" 
                                        style="width: 55px; height: 55px; object-fit: cover; margin-right: 8px;">
                                @else
                                    <img src="{{ asset('be/images/default-user.jpg') }}" alt="Default Profile">
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="min-width: 200px;">
                                <li><a class="dropdown-item"><strong class="ml-2">{{ $pelanggan->nama_pelanggan ?? 'Guest' }} - My Profile</strong></a></li>
                                <li><a class="dropdown-item" href="{{ route('fe.profile') }}"><i class="fa fa-id-card mr-2"></i> Your Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa fa-shopping-bag mr-2"></i> Your Orders</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fa fa-cog mr-2"></i> Account Settings</a></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa fa-sign-out mr-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('signout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        @else
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" style="display: flex; align-items: center;">
                                <i class="fa fa-user-o" style="font-size: 20px; margin-right: 5px;"></i>
                                <span style="margin-left: 8px;">Guest</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" style="min-width: 200px;">
                                <li><a class="dropdown-item"><strong class="ml-2">Guest</strong></a></li>
                                <li><a class="dropdown-item" href="{{ route('signin') }}"><i class="fa fa-user me-2"></i> Login</a></li>
                                <li><a class="dropdown-item" href="{{ route('signup') }}"><i class="fa fa-user-plus me-2"></i> Register</a></li>
                            </ul>
                        @endif
                    </div>
                    <!-- /Profile -->

                    <!-- Menu Toogle -->
                    <div class="menu-toggle">
                        <a href="#">
                            <i class="fa fa-bars"></i>
                            <span>Menu</span>
                        </a>
                    </div>
                    <!-- /Menu Toogle -->
                </div>
            </div>
            <!-- /ACCOUNT -->
        </div>
        <!-- row -->
    </div>
    <!-- container -->
</div>