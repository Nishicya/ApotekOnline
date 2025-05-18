@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="main-content container-fluid" style="margin-top: 20px;">
    <!-- Header Section -->
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6">
                <h1>Welcome Back {{ Auth::user()->name }}!</h1>
            </div>
            <div class="col-12 col-md-6 text-md-right">
                <div class="btn-group">
                    <a href="{{ route('pemilik.dashboard.exportPdf') }}" class="btn btn-danger btn-sm ">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('pemilik.dashboard.exportExcel') }}" class="btn btn-success btn-sm ml-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics Section -->
    <section class="section">
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-3 mb-3 mb-lg-0">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Obat</h6>
                                <h4 class="font-weight-bold mb-0">{{ $stats['totalObat'] }}</h4>
                            </div>
                            <div class="bg-primary rounded-circle p-3">
                                <i class="fas fa-pills text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3 mb-3 mb-lg-0">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Pelanggan</h6>
                                <h4 class="font-weight-bold mb-0">{{ $stats['totalPelanggan'] }}</h4>
                            </div>
                            <div class="bg-success rounded-circle p-3">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3 mb-3 mb-sm-0">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Penjualan</h6>
                                <h4 class="font-weight-bold mb-0">{{ $stats['totalPenjualan'] }}</h4>
                            </div>
                            <div class="bg-info rounded-circle p-3">
                                <i class="fas fa-shopping-cart text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Pembelian</h6>
                                <h4 class="font-weight-bold mb-0">{{ $stats['totalPembelian'] }}</h4>
                            </div>
                            <div class="bg-warning rounded-circle p-3">
                                <i class="fas fa-truck-loading text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Financial Summary Section -->
    <section class="section">
        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Pendapatan</h6>
                        <h3 class="text-success">Rp {{ number_format($stats['totalPendapatan'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Pengeluaran</h6>
                        <h3 class="text-danger">Rp {{ number_format($stats['totalPengeluaran'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sales Chart Section -->
    <section class="section">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Grafik Penjualan 12 Bulan Terakhir</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 300px;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sales and Delivery Status Pie Charts -->
    <section class="section">
        <div class="row mb-4">
            <!-- Sales Status Pie Chart -->
            <div class="col-12 col-md-6 mb-4 mb-md-0">
                <div class="card h-100">
                    <div class="card-header">
                        <h5>Status Penjualan</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 250px;">
                            <canvas id="salesStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Status Pie Chart -->
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5>Status Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 250px;">
                            <canvas id="deliveryStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Top Products and Recent Sales Section -->
    <section class="section">
        <div class="row mb-4">
            <!-- Top Obats -->
            <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>5 Obat Terlaris</h5>
                        <a href="{{ route('daftarobatpemilik.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($topObats as $obat)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $obat->nama_obat }}</strong>
                                        <div class="text-muted small">{{ $obat->jenisObat->jenis ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary rounded-pill">{{ $obat->total_terjual }} Terjual</span>
                                        <div class="text-muted small">Stok: {{ $obat->stok }}</div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>5 Penjualan Terakhir</h5>
                        <a href="{{ route('laporanpenjualan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($recentSales as $penjualan)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Nama Pelanggan: {{ $penjualan->pelanggan->nama_pelanggan ?? 'Guest' }}</strong>
                                        <div class="text-muted small">{{ $penjualan->id  }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-success">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</span>
                                        <div class="text-muted small">{{ $penjualan->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Purchases and Deliveries Section -->
    <section class="section">
        <div class="row mb-4">
            <!-- Recent Purchases -->
            <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>5 Pembelian Terakhir</h5>
                        <a href="{{ route('laporanpembelian.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($recentPurchases as $pembelian)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $pembelian->no_nota }}</strong>
                                        <div class="text-muted small">{{ $pembelian->distributor->nama_distributor ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-danger">Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</span>
                                        <div class="text-muted small">{{ $pembelian->tgl_pembelian ? Carbon\Carbon::parse($pembelian->tgl_pembelian)->format('d/m/Y') : '-' }}</div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Recent Deliveries -->
            <div class="col-12 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>5 Pengiriman Terakhir</h5>
                        <a href="{{ route('daftarpengiriman.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($recentDeliveries as $pengiriman)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>ID: {{ $pengiriman->id }}</strong>
                                        <div class="text-muted small">Penjualan #{{ $pengiriman->penjualan->id ?? '-' }}</div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $pengiriman->status_kirim == 'Tiba Di Tujuan' ? 'success' : 'warning' }}">
                                            {{ $pengiriman->status_kirim }}
                                        </span>
                                        <div class="text-muted small">{{ $pengiriman->jenisPengiriman->nama_jenis ?? '-' }}</div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: @json($salesData->pluck('month')),
                datasets: [{
                    label: 'Total Penjualan',
                    data: @json($salesData->pluck('total')),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Sales Status Pie Chart
        const salesStatusCtx = document.getElementById('salesStatusChart').getContext('2d');
        const salesStatusChart = new Chart(salesStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Selesai', 'Pending', 'Dibatalkan'],
                datasets: [{
                    data: [{{ $salesStats['completed'] ?? 0 }}, {{ $salesStats['pending'] ?? 0 }}, {{ $salesStats['cancelled'] ?? 0 }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Delivery Status Pie Chart
        const deliveryStatusCtx = document.getElementById('deliveryStatusChart').getContext('2d');
        const deliveryStatusChart = new Chart(deliveryStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Tiba Di Tujuan', 'Dalam Proses', 'Gagal'],
                datasets: [{
                    data: [{{ $deliveryStats['shipped'] ?? 0 }}, {{ $deliveryStats['processing'] ?? 0 }}, {{ $deliveryStats['failed'] ?? 0 }}],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(255, 99, 132, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection