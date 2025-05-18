<div class="az-header">
    @php
        $user = Auth::user();
        $routePrefix = $user->role;
    @endphp
    <div class="container">
        <div class="az-header-left">
            <a href="{{ url("/$routePrefix") }}" class="az-logo"><span></span>HealthifyDash</a>
            <a href="" id="azMenuShow" class="az-header-menu-icon d-lg-none"><span></span></a>
        </div>
        <!-- az-header-left -->
        <div class="az-header-menu">
            <div class="az-header-menu-header">
                <a href="{{ url("/$routePrefix") }}" class="az-logo"><span></span>HealthifyDash</a>
                <a href="" class="close">&times;</a>
            </div>
            <!-- az-header-menu-header -->
            <ul class="nav">
                <!-- Standalone Dashboard -->
                <li class="nav-item {{ request()->is('admin', 'apoteker', 'pemilik', 'karyawan', 'kasir') ? 'active show' : '' }}">
                    <a href="{{ url("/$routePrefix") }}" class="nav-link"><i class="typcn typcn-chart-area-outline"></i> Dashboard</a>
                </li>

                @if (str_contains(Auth::user()->role, 'pemilik'))
                <li class="nav-item dropdown {{ request()->Is('obat*', 'pelanggan*', 'distributor*', 'pengiriman*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="typcn typcn-tags"></i> Daftar 
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('daftarobat.index') }}" class="dropdown-item {{ request()->Is('obat*') ? 'active' : '' }}">Obat</a>
                        <a href="{{ route('daftarpelanggan.index') }}" class="dropdown-item {{ request()->Is('pelanggan*') ? 'active' : '' }}">Pelanggan</a>
                        <a href="{{ route('daftardistributor.index') }}" class="dropdown-item {{ request()->Is('distributor*') ? 'active' : '' }}">Distributor</a>
                        <a href="{{ route('daftarpengiriman.index') }}" class="dropdown-item {{ request()->Is('pengiriman*') ? 'active' : '' }}">Pengiriman</a>
                    </div>
                </li>
                <li class="nav-item dropdown {{ request()->Is('penjualan*', 'pembelian*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="typcn typcn-chart-bar-outline"></i> Laporan 
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('laporanpenjualan.index') }}" class="dropdown-item {{ request()->Is('penjualan*') ? 'active' : '' }}">Penjualan</a>
                        <a href="{{ route('laporanpembelian.index') }}" class="dropdown-item {{ request()->Is('pembelian*') ? 'active' : '' }}">Pembelian</a>
                    </div>
                </li>
                @endif

                @if (str_contains(Auth::user()->role, 'admin'))
                <!-- User Management Dropdown -->
                <li class="nav-item dropdown {{ request()->Is('user*', 'pelanggan*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="typcn typcn-book"></i> User Management
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('user.manage') }}" class="dropdown-item {{ request()->Is('user*') ? 'active' : '' }}">Users</a>
                        <a href="{{ route('pelanggan.manage') }}" class="dropdown-item {{ request()->Is('pelanggan*') ? 'active' : '' }}">Pelanggan</a>
                    </div>
                </li>

                <!-- Data Master Dropdown -->
                <li class="nav-item dropdown {{ request()->Is('distributor*', 'obat*', 'jenis-obat*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="typcn typcn-tags"></i> Data Master
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('distributor.index') }}" class="dropdown-item {{ request()->Is('distributor*') ? 'active' : '' }}">Distributor</a>
                        <a href="{{ route('obat.manage') }}" class="dropdown-item {{ request()->Is('obat*') ? 'active' : '' }}">Obat</a>
                        <a href="{{ route('jenis-obat.manage') }}" class="dropdown-item {{ request()->Is('jenis-obat*') ? 'active' : '' }}">Kategori Obat</a>
                    </div>
                </li>

                <!-- Transaction Dropdown -->
                <li class="nav-item dropdown {{ request()->Is('penjualan*', 'pembelian*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="typcn typcn-credit-card"></i> Transactions
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('penjualan.manage') }}" class="dropdown-item {{ request()->Is('penjualan*') ? 'active' : '' }}">Penjualan</a>
                        <a href="{{ route('pembelian.manage') }}" class="dropdown-item {{ request()->Is('pembelian*') ? 'active' : '' }}">Pembelian</a>
                    </div>
                </li>

                <!-- Logistics Dropdown -->
                <li class="nav-item dropdown {{ request()->Is('pengiriman*') ? 'active show' : '' }}">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="typcn typcn-chart-bar-outline"></i> Logistics
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('pengiriman.manage') }}" class="dropdown-item {{ request()->Is('pengiriman*') ? 'active' : '' }}">Pengiriman</a>
                        <a href="{{ route('jenis-pengiriman.manage') }}" class="dropdown-item {{ request()->Is('jenis-pengiriman*') ? 'active' : '' }}">Jenis Pengiriman</a>
                    </div>
                </li>
                @endif

                @if (str_contains(Auth::user()->role, 'kasir'))
                <li class="nav-item {{ request()->is('penjualan') ? 'active show' : '' }}">
                    <a href="{{ route('penjualan.manage') }}" class="nav-link"><i class="typcn typcn-chart-bar-outline"></i> Penjualan</a>
                </li>
                <li class="nav-item {{ request()->Is('obat*') ? 'active' : '' }}">
                    <a href="{{ route('daftarobat.index') }}" class="nav-link"><i class="typcn typcn-tags"></i>Daftar Obat</a>
                </li>
                @endif

                @if (str_contains(Auth::user()->role, 'apoteker'))
                <li class="nav-item {{ request()->Is('obat*') ? 'active' : '' }}">
                    <a href="{{ route('obat.manage') }}" class="nav-link"><i class="typcn typcn-tags"></i>Obat</a>
                </li>
                <li class="nav-item {{ request()->Is('pembelian*') ? 'active' : '' }}">
                    <a href="{{ route('pembelian.manage') }}" class="nav-link"><i class="typcn typcn-tags"></i>Pembelian</a>
                </li>
                <li class="nav-item {{ request()->Is('jenis-obat*') ? 'active' : '' }}">
                    <a href="{{ route('jenis-obat.manage') }}" class="nav-link"><i class="typcn typcn-tags"></i>Kategori Obat</a>
                </li>
                @endif

                @if (str_contains(Auth::user()->role, 'karyawan'))
                <li class="nav-item {{ request()->Is('pengiriman') ? 'active show' : '' }}">
                    <a href="{{ route('pengiriman.manage') }}" class="nav-link"><i class="typcn typcn-chart-bar-outline"></i>Pengiriman</a>
                </li>
                <li class="nav-item {{ request()->Is('jenis-pengiriman') ? 'active show' : '' }}">
                    <a href="{{ route('jenis-pengiriman.manage') }}" class="nav-link"><i class="typcn typcn-chart-bar-outline"></i>Jenis Pengiriman</a>
                </li>
                @endif
            </ul>
        </div>
        <!-- az-header-menu -->
        
        <!-- az-header-profile -->
        <div class="dropdown az-profile-menu">
            <a href="" class="az-img-user">
                @if($user && $user->foto)
                <img src="{{ asset('storage/'.$user->foto) }}" alt="Profile Photo">
                @else
                <img src="{{ asset('images/default-user.jpg') }}" alt="">
                @endif
            </a>
        <div class="dropdown-menu">
            <div class="az-dropdown-header d-sm-none">
                <a href="" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
            </div>
            <div class="az-header-profile">
                <div class="az-img-user">
                    @if($user && $user->foto)
                    <img src="{{ asset('storage/'.$user->foto) }}" alt="Profile Photo">
                    @else
                    <img src="{{ asset('images/default-user.jpg') }}" alt="">
                    @endif
                </div>
                <h6>{{ $user->name }}</h6>
                <span>{{ ucfirst($user->role) }}</span>
                </div>
                <a href="{{ route('be.profile') }}" class="dropdown-item"><i class="typcn typcn-user-outline"></i> My Profile</a>
                <a href="{{ route('be.profile') }}" class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="typcn typcn-power-outline"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
        <!-- az-header-profile -->
    </div>
    <!-- container -->
</div>
<!-- az-header -->