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
            <p class="product-category">{{ $obat->jenisObat->nama_jenis }}</p>
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
                <button class="add-to-cart-btn" data-id="{{ $obat->id }}"><i class="fa fa-shopping-cart"></i> add to cart</button>
            @else
                <button class="add-to-cart-btn" disabled><i class="fa fa-shopping-cart"></i> Out of Stock</button>
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