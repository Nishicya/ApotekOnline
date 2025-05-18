@extends('fe.master')

@section('header')
    @include('fe.header')
@endsection

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- ASIDE -->
            <div id="aside" class="col-md-3">
                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Categories</h3>
                    <div class="checkbox-filter">
                        @foreach($jenisObats as $jenis)
                        <div class="input-checkbox">
                            <input type="checkbox" id="category-{{ $jenis->id }}">
                            <label for="category-{{ $jenis->id }}">
                                <span></span>
                                {{ $jenis->jenis }}
                                <small>({{ $jenis->obats->count() }})</small>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Price</h3>
                    <div class="price-filter">
                        <div id="price-slider"></div>
                        <div class="input-number price-min">
                            <input id="price-min" type="number" value="{{ $minPrice }}">
                            <span class="qty-up">+</span>
                            <span class="qty-down">-</span>
                        </div>
                        <span>-</span>
                        <div class="input-number price-max">
                            <input id="price-max" type="number" value="{{ $maxPrice }}">
                            <span class="qty-up">+</span>
                            <span class="qty-down">-</span>
                        </div>
                    </div>
                </div>
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Top selling</h3>
                    @foreach($topSelling as $obat)
                    <div class="product-widget">
                        <div class="product-img">
                            <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                        </div>
                        <div class="product-body">
                            <p class="product-category">{{ $obat->jenisObat->jenis }}</p>
                            <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                            <h4 class="product-price">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!-- /aside Widget -->
            </div>
            <!-- /ASIDE -->

     
            <!-- STORE -->
            <div id="store" class="col-md-9">
                <!-- store top filter -->
                <div class="store-filter clearfix">
                    <div class="store-sort">
                        <label>
                            Sort By:
                            <select class="input-select" id="sort-by">
                                <option value="popular">Popular</option>
                                <option value="newest">Newest</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                            </select>
                        </label>

                        <label>
                            Show:
                            <select class="input-select" id="show-count">
                                <option value="12">12</option>
                                <option value="24">24</option>
                                <option value="36">36</option>
                            </select>
                        </label>
                    </div>
                    <ul class="store-grid">
                        <a href="{{ route('home') }}" class="btn btn-danger">
                            <i class="fa fa-arrow-left"></i> Back to Home
                        </a>
                    </ul>
                </div>
                <!-- /store top filter -->

                <!-- store products -->
                <div class="row" id="product-container">
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

                    @if($obats->hasPages())
                    <div class="col-md-12">
                        <div class="store-filter clearfix">
                            <span class="store-qty">Showing {{ $obats->count() }} of {{ $obats->total() }} products</span>
                            <ul class="store-pagination">
                                {{ $obats->links() }}
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- /store products -->

                <!-- store bottom filter -->
                <div class="store-filter clearfix">
                    <span class="store-qty">Showing {{ $obats->count() }} of {{ $totalProducts }} products</span>
                    <ul class="store-pagination">
                        {{ $obats->links() }}
                    </ul>
                </div>
                <!-- /store bottom filter -->
            </div>
            <!-- /STORE -->
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

@section('footer')
    @include('fe.footer')
@endsection