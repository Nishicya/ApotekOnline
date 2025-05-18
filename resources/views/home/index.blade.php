@extends('fe.master')

@section('header')
    @include('fe.header')
@endsection

@section('navbar')
    @include('fe.navbar')
@endsection

@section('banner')
    @include('fe.banner')
@endsection

@section('content')
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">New Products</h3>
                    <div class="section-nav">
                        <ul class="section-tab-nav tab-nav">
                            @foreach($obats->unique('id_jenis')->take(4) as $obat)
                                <li class="{{ $loop->first ? 'active' : '' }}">
                                    <a data-toggle="tab" href="#tab{{ $obat->id_jenis }}">{{ $obat->jenisObat->nama_jenis }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /section title -->

            <!-- Products tab & slick -->
            <div class="col-md-12">
                <div class="row">
                    <div class="products-tabs">
                        <!-- tab -->
                        <div id="tab1" class="tab-pane active">
                            <div class="products-slick" data-nav="#slick-nav-1">
                                @foreach($obats as $obat)
                                <!-- product -->
                                <div class="col-md-4 col-xs-6">
                                    <div class="product">
                                        <div class="product-img">
                                            <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                                            <div class="product-label">
                                                @if($obat->stok <= 0)
                                                    <span class="sale">SOLD OUT</span>
                                                @elseif(now()->diffInDays($obat->created_at) <= 7)
                                                    <span class="new">NEW</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $obat->jenisObat->jenis }}</p>
                                            <h3 class="product-name"><a href="{{ route('product.detail', $obat->id) }}">{{ $obat->nama_obat }}</a></h3>
                                            <h4 class="product-price">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                                            <div class="product-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= 4)
                                                        <i class="fa fa-star"></i>
                                                    @else
                                                        <i class="fa fa-star-o"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small>Sold: {{ $obat->total_sold ?? 0 }}</small>
                                        </div>
                                        <div class="add-to-cart">
                                            @if($obat->stok > 0)
                                                @auth('pelanggan')
                                                    <button class="btn btn-primary add-to-cart-btn" data-product-id="{{ $obat->id }}">
                                                        <i class="fa fa-shopping-cart"></i> <span class="btn-text">Add to Cart</span>
                                                    </button>
                                                @else
                                                    <button class="btn btn-primary" onclick="showLoginAlert()">
                                                        <i class="fa fa-shopping-cart"></i> <span class="btn-text">Add to Cart</span>
                                                    </button>
                                                @endauth
                                            @else
                                                <button class="btn btn-secondary" disabled>
                                                    <i class="fa fa-times-circle"></i> Stok Habis
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- /product -->
                                @endforeach
                            </div>
                            <div id="slick-nav-1" class="products-slick-nav"></div>
                        </div>
                        <!-- /tab -->
                    </div>
                </div>
            </div>
            <!-- Products tab & slick -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<!-- HOT DEAL SECTION -->
<div id="hot-deal" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="hot-deal">
                    <ul class="hot-deal-countdown">
                        <!-- Timer bisa diambil dari database jika ada promo -->
                        <li><div><h3 id="days">02</h3><span>Days</span></div></li>
                        <li><div><h3 id="hours">10</h3><span>Hours</span></div></li>
                        <li><div><h3 id="minutes">34</h3><span>Mins</span></div></li>
                        <li><div><h3 id="seconds">60</h3><span>Secs</span></div></li>
                    </ul>
                    <h2 class="text-uppercase">Promo Obat Bebas Terbatas</h2>
                    <p>Diskon hingga 30% untuk obat kategori tertentu</p>
                    <a class="primary-btn cta-btn" href="{{ route('shop', ['category' => 'Obat Bebas Terbatas']) }}">Beli Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /HOT DEAL SECTION -->

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Top selling</h3>
                    <div class="section-nav">
                        <ul class="section-tab-nav tab-nav">
                            @foreach($obats->unique('id_jenis')->take(4) as $obat)
                                <li class="{{ $loop->first ? 'active' : '' }}">
                                    <a data-toggle="tab" href="#tab{{ $obat->id_jenis + 10 }}">{{ $obat->jenisObat->nama_jenis }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /section title -->

            <!-- Products tab & slick -->
            <div class="col-md-12">
                <div class="row">
                    <div class="products-tabs">
                        <!-- tab -->
                        <div id="tab2" class="tab-pane fade in active">
                            <div class="products-slick" data-nav="#slick-nav-2">
                                @foreach($obats->sortByDesc(function($obat) {
                                    return $obat->detailPenjualans->sum('jumlah');
                                })->take(4) as $obat)
                                <!-- product -->
                                <div class="col-md-4 col-xs-6">
                                    <div class="product">
                                        <div class="product-img">
                                            <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                                            <div class="product-label">
                                                @if($obat->stok <= 0)
                                                    <span class="sale">SOLD OUT</span>
                                                @elseif(now()->diffInDays($obat->created_at) <= 7)
                                                    <span class="new">NEW</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $obat->jenisObat->jenis }}</p>
                                            <h3 class="product-name"><a href="{{ route('product.detail', $obat->id) }}">{{ $obat->nama_obat }}</a></h3>
                                            <h4 class="product-price">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                                            <div class="product-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= 4)
                                                        <i class="fa fa-star"></i>
                                                    @else
                                                        <i class="fa fa-star-o"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small>Sold: {{ $obat->total_sold ?? 0 }}</small>
                                        </div>
                                        <div class="add-to-cart">
                                            @if($obat->stok > 0)
                                                @auth('pelanggan')
                                                    <button class="btn btn-primary add-to-cart-btn" data-product-id="{{ $obat->id }}">
                                                        <i class="fa fa-shopping-cart"></i> <span class="btn-text">Add to Cart</span>
                                                    </button>
                                                @else
                                                    <button class="btn btn-primary" onclick="showLoginAlert()">
                                                        <i class="fa fa-shopping-cart"></i> <span class="btn-text">Add to Cart</span>
                                                    </button>
                                                @endauth
                                            @else
                                                <button class="btn btn-secondary" disabled>
                                                    <i class="fa fa-times-circle"></i> Stok Habis
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!-- /product -->
                                @endforeach
                            </div>
                            <div id="slick-nav-2" class="products-slick-nav"></div>
                        </div>
                        <!-- /tab -->
                    </div>
                </div>
            </div>
            <!-- /Products tab & slick -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-4 col-xs-6">
                <div class="section-title">
                    <h4 class="title">Top selling</h4>
                    <div class="section-nav">
                        <div id="slick-nav-3" class="products-slick-nav"></div>
                    </div>
                </div>

                <div class="products-widget-slick" data-nav="#slick-nav-3">
                    <div>
                        @foreach($obats->sortByDesc(function($obat) {
                            return $obat->detailPenjualans->sum('jumlah');
                        })->take(3) as $obat)
                        <!-- product widget -->
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <!-- /product widget -->
                        @endforeach
                    </div>

                    <div>
                        @foreach($obats->sortByDesc(function($obat) {
                            return $obat->detailPenjualans->sum('jumlah');
                        })->skip(3)->take(3) as $obat)
                        <!-- product widget -->
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <!-- /product widget -->
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-xs-6">
                <div class="section-title">
                    <h4 class="title">Recently Added</h4>
                    <div class="section-nav">
                        <div id="slick-nav-4" class="products-slick-nav"></div>
                    </div>
                </div>

                <div class="products-widget-slick" data-nav="#slick-nav-4">
                    <div>
                        @foreach($obats->sortByDesc('created_at')->take(3) as $obat)
                        <!-- product widget -->
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <!-- /product widget -->
                        @endforeach
                    </div>

                    <div>
                        @foreach($obats->sortByDesc('created_at')->skip(3)->take(3) as $obat)
                        <!-- product widget -->
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <!-- /product widget -->
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="clearfix visible-sm visible-xs"></div>

            <div class="col-md-4 col-xs-6">
                <div class="section-title">
                    <h4 class="title">Special Offers</h4>
                    <div class="section-nav">
                        <div id="slick-nav-5" class="products-slick-nav"></div>
                    </div>
                </div>

                <div class="products-widget-slick" data-nav="#slick-nav-5">
                    <div>
                        @foreach($obats->where('stok', '<=', 10)->take(3) as $obat)
                        <!-- product widget -->
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                                <div class="product-label">
                                    <span class="sale">LIMITED</span>
                                </div>
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <!-- /product widget -->
                        @endforeach
                    </div>

                    <div>
                        @foreach($obats->where('stok', '<=', 10)->skip(3)->take(3) as $obat)
                        <!-- product widget -->
                        <div class="product-widget">
                            <div class="product-img">
                                <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                                <div class="product-label">
                                    <span class="sale">LIMITED</span>
                                </div>
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                        <!-- /product widget -->
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Use event delegation for dynamically loaded elements
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        let button = $(this);
        let productId = button.data('product-id');
        let quantity = 1;

        // Change button state during loading
        button.prop('disabled', true);
        button.addClass('btn-loading');
        button.find('.btn-text').text('Menambahkan...');

        $.ajax({
            url: '{{ route("keranjang.add") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id_obat: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    // Update cart count in navbar
                    $('.cart-count').text(response.cart_count);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message
                    });

                    if (response.login_required) {
                        setTimeout(() => {
                            window.location.href = '{{ route("signin") }}';
                        }, 2000);
                    }
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            },
            complete: function() {
                button.prop('disabled', false);
                button.removeClass('btn-loading');
                button.find('.btn-text').text('Add to Cart');
            }
        });
    });

    // Define the login alert function
    window.showLoginAlert = function() {
        Swal.fire({
            icon: 'info',
            title: 'Login Required',
            text: 'Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang',
            confirmButtonText: 'Login',
            showCancelButton: true,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("signin") }}';
            }
        });
    };
});
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .product-size {
        margin: 5px 0;
        font-size: 12px;
        color: #666;
    }

    .product-size span {
        display: inline-block;
        padding: 2px 5px;
        background: #f5f5f5;
        border-radius: 3px;
    }

    .product-label .sale {
        background-color: #f64747; /* Warna merah untuk SOLD OUT */
    }

    .product-label .new {
        background-color: #2ecc71; /* Warna hijau untuk NEW */
    }
    
    .add-to-cart-btn {
        pointer-events: auto !important;
    }

    .btn-loading {
    position: relative;
    }
    .btn-loading:after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -8px 0 0 -8px;
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('newsletter')
    @include('fe.newsletter')
@endsection

@section('footer')
    @include('fe.footer')
@endsection