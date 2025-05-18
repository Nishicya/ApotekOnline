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
            <!-- Product main img -->
            <div class="col-md-5 col-md-push-2">
                <div id="product-main-img">
                    @if($obat->foto1)
                    <div class="product-preview">
                        <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                    </div>
                    @endif
                    
                    @if($obat->foto2)
                    <div class="product-preview">
                        <img src="{{ asset('storage/' . $obat->foto2) }}" alt="{{ $obat->nama_obat }}">
                    </div>
                    @endif
                    
                    @if($obat->foto3)
                    <div class="product-preview">
                        <img src="{{ asset('storage/' . $obat->foto3) }}" alt="{{ $obat->nama_obat }}">
                    </div>
                    @endif
                </div>
            </div>
            <!-- /Product main img -->

            <!-- Product thumb imgs -->
            <div class="col-md-2 col-md-pull-5">
                <div id="product-imgs">
                    @if($obat->foto1)
                    <div class="product-preview">
                        <img src="{{ asset('storage/' . $obat->foto1) }}" alt="{{ $obat->nama_obat }}">
                    </div>
                    @endif
                    
                    @if($obat->foto2)
                    <div class="product-preview">
                        <img src="{{ asset('storage/' . $obat->foto2) }}" alt="{{ $obat->nama_obat }}">
                    </div>
                    @endif
                    
                    @if($obat->foto3)
                    <div class="product-preview">
                        <img src="{{ asset('storage/' . $obat->foto3) }}" alt="{{ $obat->nama_obat }}">
                    </div>
                    @endif
                </div>
            </div>
            <!-- /Product thumb imgs -->

            <!-- Product details -->
            <div class="col-md-5">
                <div class="product-details">
                    <h2 class="product-name">{{ $obat->nama_obat }}</h2>
                    <div>
                        <div class="product-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= 4) <!-- Assuming average rating of 4 for demo -->
                                    <i class="fa fa-star"></i>
                                @else
                                    <i class="fa fa-star-o"></i>
                                @endif
                            @endfor
                        </div>
                        <a class="review-link">10 Review(s)</a>
                    </div>
                    <div>
                        <h3 class="product-price">Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</h3>
                        <span class="product-available {{ $obat->stok <= 0 ? 'text-danger' : 'text-success' }}">
                            {{ $obat->stok <= 0 ? 'Out of Stock' : 'In Stock' }}
                        </span>
                    </div>
                    <p>{{ $obat->deskripsi_obat }}</p>

                    <div class="add-to-cart">
                        <div class="qty-label">
                            Qty
                            <div class="input-number">
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $obat->stok }}">
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                        </div>
                        @if($obat->stok > 0)
                            <button class="add-to-cart-btn" data-id="{{ $obat->id }}"><i class="fa fa-shopping-cart"></i> add to cart</button>
                        @else
                            <button class="add-to-cart-btn" disabled><i class="fa fa-shopping-cart"></i> Out of Stock</button>
                        @endif
                    </div>

                    <ul class="product-links">
                        <li>Category:</li>
                        <li><a href="#">{{ $obat->jenisObat->jenis }}</a></li>
                    </ul>

                    <ul class="product-links">
                        <li>Share:</li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-whatsapp"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- /Product details -->

            <!-- Back Button -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <button onclick="window.history.back()" class="btn btn-danger">
                        <i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
            </div>
            <!-- /Back Button -->

            <!-- Product tab -->
            <div class="col-md-12">
                <div id="product-tab">
                    <!-- product tab nav -->
                    <ul class="tab-nav">
                        <li class="active"><a data-toggle="tab" href="#tab1">Description</a></li>
                        <li><a data-toggle="tab" href="#tab2">Details</a></li>
                        <li><a data-toggle="tab" href="#tab3" id="reviews-tab">Reviews (3)</a></li>
                    </ul>
                    <!-- /product tab nav -->

                    <!-- product tab content -->
                    <div class="tab-content">
                        <!-- tab1  -->
                        <div id="tab1" class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>{{ $obat->deskripsi_obat }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- /tab1  -->

                        <!-- tab2  -->
                        <div id="tab2" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Product Details</h4>
                                    <ul>
                                        <li><strong>Medicine Name:</strong> {{ $obat->nama_obat }}</li>
                                        <li><strong>Category:</strong> {{ $obat->jenisObat->nama_jenis }}</li>
                                        <li><strong>Price:</strong> Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</li>
                                        <li><strong>Stock:</strong> {{ $obat->stok }} items available</li>
                                        <li><strong>Added On:</strong> {{ $obat->created_at->format('d M Y') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /tab2  -->

                        <!-- tab3  -->
                        <div id="tab3" class="tab-pane fade in">
                            <div class="row">
                                <!-- Rating -->
                                <div class="col-md-3">
                                    <div id="rating">
                                        <div class="rating-avg">
                                            <span>4.5</span>
                                            <div class="rating-stars">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star-o"></i>
                                            </div>
                                        </div>
                                        <ul class="rating">
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                <div class="rating-progress">
                                                    <div style="width: 80%;"></div>
                                                </div>
                                                <span class="sum">3</span>
                                            </li>
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="rating-progress">
                                                    <div style="width: 60%;"></div>
                                                </div>
                                                <span class="sum">2</span>
                                            </li>
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="rating-progress">
                                                    <div></div>
                                                </div>
                                                <span class="sum">0</span>
                                            </li>
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="rating-progress">
                                                    <div></div>
                                                </div>
                                                <span class="sum">0</span>
                                            </li>
                                            <li>
                                                <div class="rating-stars">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                    <i class="fa fa-star-o"></i>
                                                </div>
                                                <div class="rating-progress">
                                                    <div></div>
                                                </div>
                                                <span class="sum">0</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /Rating -->

                                <!-- Reviews -->
                                <div class="col-md-6">
                                    <div id="reviews">
                                        <ul class="reviews">
                                            @for($i = 1; $i <= 3; $i++)
                                            <li>
                                                <div class="review-heading">
                                                    <h5 class="name">Customer {{ $i }}</h5>
                                                    <p class="date">27 DEC 2018, 8:0 PM</p>
                                                    <div class="review-rating">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star-o empty"></i>
                                                    </div>
                                                </div>
                                                <div class="review-body">
                                                    <p>This medicine worked great for my condition. I highly recommend it.</p>
                                                </div>
                                            </li>
                                            @endfor
                                        </ul>
                                        <ul class="reviews-pagination">
                                            <li class="active">1</li>
                                            <li><a href="#">2</a></li>
                                            <li><a href="#">3</a></li>
                                            <li><a href="#">4</a></li>
                                            <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- /Reviews -->

                                <!-- Review Form -->
                                <div class="col-md-3">
                                    <div id="review-form">
                                        <form class="review-form">
                                            <input class="input" type="text" placeholder="Your Name">
                                            <input class="input" type="email" placeholder="Your Email">
                                            <textarea class="input" placeholder="Your Review"></textarea>
                                            <div class="input-rating">
                                                <span>Your Rating: </span>
                                                <div class="stars">
                                                    <input id="star5" name="rating" value="5" type="radio"><label for="star5"></label>
                                                    <input id="star4" name="rating" value="4" type="radio"><label for="star4"></label>
                                                    <input id="star3" name="rating" value="3" type="radio"><label for="star3"></label>
                                                    <input id="star2" name="rating" value="2" type="radio"><label for="star2"></label>
                                                    <input id="star1" name="rating" value="1" type="radio"><label for="star1"></label>
                                                </div>
                                            </div>
                                            <button class="primary-btn">Submit</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- /Review Form -->
                            </div>
                        </div>
                        <!-- /tab3  -->
                    </div>
                    <!-- /product tab content  -->
                </div>
            </div>
            <!-- /product tab -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<!-- Section -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="title">Related Products</h3>
                </div>
            </div>

            @foreach($relatedProducts as $related)
            <!-- product -->
            <div class="col-md-3 col-xs-6">
                <div class="product">
                    <div class="product-img">
                        <img src="{{ asset('storage/' . $related->foto1) }}" alt="{{ $related->nama_obat }}">
                        @if($related->stok <= 0)
                            <div class="product-label">
                                <span class="sale">SOLD OUT</span>
                            </div>
                        @elseif(now()->diffInDays($related->created_at) <= 7)
                            <div class="product-label">
                                <span class="new">NEW</span>
                            </div>
                        @endif
                    </div>
                    <div class="product-body">
                        <p class="product-category">{{ $related->jenisObat->jenis }}</p>
                        <h3 class="product-name"><a href="{{ route('product.detail', $related->id) }}">{{ $related->nama_obat }}</a></h3>
                        <h4 class="product-price">Rp{{ number_format($related->harga_jual, 0, ',', '.') }}</h4>
                        <div class="product-rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= 4)
                                    <i class="fa fa-star"></i>
                                @else
                                    <i class="fa fa-star-o"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="add-to-cart">
                        @if($related->stok > 0)
                            <button class="add-to-cart-btn" data-id="{{ $related->id }}"><i class="fa fa-shopping-cart"></i> add to cart</button>
                        @else
                            <button class="add-to-cart-btn" disabled><i class="fa fa-shopping-cart"></i> Out of Stock</button>
                        @endif
                    </div>
                </div>
            </div>
            <!-- /product -->
            @endforeach
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /Section -->

@push('scripts')
<script>
    $(document).ready(function() {
        // Quantity input
        $('.qty-up').click(function(e) {
            e.preventDefault();
            var input = $(this).siblings('input');
            var max = parseInt(input.attr('max')) || 999;
            var value = parseInt(input.val());
            if (value < max) {
                input.val(value + 1).change();
            }
        });

        $('.qty-down').click(function(e) {
            e.preventDefault();
            var input = $(this).siblings('input');
            var value = parseInt(input.val());
            if (value > 1) {
                input.val(value - 1).change();
            }
        });

        // Add to cart functionality
        $('.add-to-cart-btn').click(function() {
            if ($(this).is(':disabled')) return;
            
            const obatId = $(this).data('id');
            const quantity = $('#quantity').val();
            
            $.ajax({
                url: '{{ route("keranjang.add") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id_obat: obatId,
                    quantity: quantity
                },
                success: function(response) {
                    if(response.success) {
                        alert('Product added to cart!');
                        // Update cart count
                        $('.cart-count').text(response.cartCount);
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error adding product to cart');
                }
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .btn-back {
        background-color: #f2f2f2;
        color: #333;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: bold;
    }
    .btn-back:hover {
        background-color: #e6e6e6;
        color: #000;
    }
    .btn-back i {
        margin-right: 8px;
    }
    .product-available.text-success {
        color: #2ecc71;
    }
    .product-available.text-danger {
        color: #f64747;
    }
    .add-to-cart-btn[disabled] {
        background-color: #ccc;
        cursor: not-allowed;
    }
</style>
@endpush
@endsection

@section('footer')
    @include('fe.footer')
@endsection