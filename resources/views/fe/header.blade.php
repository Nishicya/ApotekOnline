<div id="header">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- LOGO -->
            <div class="col-md-3">
                <div class="header-logo">
                    <a href="/home" class="logo">
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
                            <option value="0" disabled selected>All Categories</option>
                            <option value="1">Bebas</option>
                            <option value="1">Terbatas</option>
                            <option value="1">Keras</option>
                            <option value="1" >Herbal</option>
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
                            <span class="keranjang-count qty">{{ auth('pelanggan')->user() ? auth('pelanggan')->user()->keranjangs->count() : 0 }}</span>
                        </a>
                        <div class="cart-dropdown">
                            <div class="cart-list">
                                @if(auth('pelanggan')->user())
                                    @php
                                        $cartItems = auth('pelanggan')->user()->keranjangs()
                                            ->with('obat')
                                            ->latest()
                                            ->take(5)
                                            ->get();
                                    @endphp
                                    
                                    @forelse($cartItems as $item)
                                        <div class="product-widget">
                                            <div class="product-img">
                                                <img src="{{ asset('storage/' . $item->obat->foto1) }}" alt="{{ $item->obat->nama_obat }}">
                                            </div>
                                            <div class="product-body">
                                                <h3 class="product-name">
                                                    <a href="{{ route('product.detail', $item->obat->id) }}">{{ $item->obat->nama_obat }}</a>
                                                </h3>
                                                <h4 class="product-price">
                                                    <span class="qty">{{ $item->jumlah_order }}x</span>
                                                    Rp{{ number_format($item->harga, 0, ',', '.') }}
                                                </h4>
                                            </div>
                                            <button class="delete" onclick="removeFromCart({{ $item->id }})">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </div>
                                    @empty
                                        <div class="empty-cart-message">
                                            <p>Keranjang belanja kosong</p>
                                        </div>
                                    @endforelse
                                @else
                                    <div class="empty-cart-message">
                                        <p>Silakan login untuk melihat keranjang</p>
                                    </div>
                                @endif
                            </div>
                            
                            @if(auth('pelanggan')->user())
                                <div class="cart-summary">
                                    <small>{{ $cartItems->count() }} Item(s) selected</small>
                                    <h5>SUBTOTAL: Rp{{ number_format(auth('pelanggan')->user()->keranjangs->sum('subtotal'), 0, ',', '.') }}</h5>
                                </div>
                                <div class="cart-btns">
                                    <a href="{{ route('keranjang') }}">View Cart</a>
                                    <a href="{{ route('checkout') }}">Checkout <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            @endif
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
                                <li><a class="dropdown-item" href="{{ route('fe.pesanan') }}"><i class="fa fa-shopping-bag mr-2"></i> My Order</a></li>
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
@push('scripts')
<script>
    function removeFromCart(cartId) {
        Swal.fire({
            title: 'Hapus dari keranjang?',
            text: "Produk akan dihapus dari keranjang belanja",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/keranjang/remove/' + cartId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            Swal.fire(
                                'Dihapus!',
                                'Produk telah dihapus dari keranjang.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    }
</script>
@endpush

@push('styles')
<style>
    .cart-dropdown {
        width: 350px;
        padding: 15px;
    }
    .product-widget {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .product-img {
        width: 60px;
        height: 60px;
        margin-right: 15px;
    }
    .product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-body {
        flex: 1;
    }
    .product-name {
        font-size: 14px;
        margin-bottom: 5px;
    }
    .product-price {
        font-size: 14px;
        color: #D10024;
    }
    .delete {
        background: none;
        border: none;
        color: #D10024;
        cursor: pointer;
    }
    .empty-cart-message {
        text-align: center;
        padding: 20px;
        color: #666;
    }
    .cart-summary {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    .cart-btns {
        margin-top: 15px;
        display: flex;
        justify-content: space-between;
    }
    .cart-btns a {
        padding: 8px 15px;
        border-radius: 3px;
    }
    .cart-btns a:first-child {
        background: #f1f1f1;
        color: #333;
    }
    .cart-btns a:last-child {
        background: #D10024;
        color: white;
    }
</style>
@endpush