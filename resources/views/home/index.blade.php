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
                                @foreach($obats->sortByDesc('created_at')->take(5) as $obat)
                                <!-- product -->
                                <div class="product">
                                    <div class="product-img">
                                        <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                                        @if($obat->created_at->diffInDays(now()) < 7)
                                            <div class="product-label">
                                                <span class="new">NEW</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                        <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                        <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                                        <div class="product-rating">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fa fa-star{{ $i < 4 ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="product-btns">
                                            <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                                            <button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
                                            <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
                                        </div>
                                    </div>
                                    <div class="add-to-cart">
                                        <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
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
                                <div class="product">
                                    <div class="product-img">
                                        <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                                        @if($obat->detailPenjualans->sum('jumlah') > 50)
                                            <div class="product-label">
                                                <span class="sale">HOT</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
                                        <h3 class="product-name"><a href="#">{{ $obat->nama_obat }}</a></h3>
                                        <h4 class="product-price">Rp {{ number_format($obat->harga_jual, 0, ',', '.') }}</h4>
                                        <div class="product-rating">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fa fa-star{{ $i < 4 ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="product-btns">
                                            <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                                            <button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
                                            <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
                                        </div>
                                    </div>
                                    <div class="add-to-cart">
                                        <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
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
@endsection

@section('newsletter')
    @include('fe.newsletter')
@endsection

@section('footer')
    @include('fe.footer')
@endsection