<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            @foreach($jenisObats as $jenisObat)
            <!-- shop -->
            <div class="col-md-4 col-xs-6">
                <div class="shop">
                    <div class="shop-img">
                        <img src="{{ asset('storage/' . $jenisObat->image_url) }}" alt="{{ $jenisObat->jenis }}">
                    </div>
                    <div class="shop-body">
                        <h3>{{ $jenisObat->jenis }}</h3>
                        <a href="{{ route('shop', ['category' => $jenisObat->jenis]) }}" class="cta-btn">
                            Beli Sekarang <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /shop -->
            @endforeach
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>