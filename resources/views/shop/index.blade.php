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
<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Our Medicine Products</h3>
                </div>
            </div>
            <!-- /section title -->

            <!-- Products -->
            <div class="col-md-12">
                <div class="row">
                    @php
                        $displayedObats = $obats->take(20);  
                    @endphp

                    @foreach($displayedObats as $obat)
                    <!-- Product -->
                    <div class="col-md-3 col-xs-6">
                        <div class="product">
                            <div class="product-img">
                                <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                                @if($obat->stok > 0)
                                    <div class="product-label">
                                        @if($obat->stok < 10)
                                            <span class="sale">LIMITED</span>
                                        @endif
                                        <span class="new">NEW</span>
                                    </div>
                                @else
                                    <div class="product-label">
                                        <span class="sale">SOLD OUT</span>
                                    </div>
                                @endif
                            </div>
                            <div class="product-body">
                                <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                                <div class="product-size">
                                    <span>Size: Standard</span>
                                </div>
                                <div class="product-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star{{ $i <= 4 ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <div class="product-btns">
                                    <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                                    <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
                                </div>
                            </div>
                            <div class="add-to-cart">
                                @if($obat->stok > 0)
                                    <button class="add-to-cart-btn" data-id="{{ $obat->id }}"><i class="fa fa-shopping-cart"></i> add to cart</button>
                                @else
                                    <button class="add-to-cart-btn" disabled><i class="fa fa-shopping-cart"></i> Out of Stock</button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /Product -->
                    @endforeach
                </div>
            </div>
            <!-- /Products -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

@push('styles')
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
</style>
@endpush
@endsection

@section('footer')
    @include('fe.footer')
@endsection