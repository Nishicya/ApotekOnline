@extends('fe.master')

@section('header')
    @include('fe.header')
@endsection

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="section" style="background: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mb-4">
                    <h3 class="title" style="font-weight:700;">Your Order Details</h3>
                    <span class="badge badge-pill badge-primary" style="font-size:13px;">
                        {{ $penjualan->pengiriman->no_invoice ?? '-' }}
                    </span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-6"><strong>Order Date:</strong></div>
                            <div class="col-6">{{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d/m/Y') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><strong>Shipping Date:</strong></div>
                            <div class="col-6">
                                @if($penjualan->pengiriman && $penjualan->pengiriman->tgl_tiba)
                                    {{ \Carbon\Carbon::parse($penjualan->pengiriman->tgl_tiba)->format('d/m/Y') }}
                                @elseif($penjualan->pengiriman && $penjualan->pengiriman->tgl_kirim)
                                    {{ \Carbon\Carbon::parse($penjualan->pengiriman->tgl_kirim)->addDays(2)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><strong>Order Status:</strong></div>
                            <div class="col-6">
                                <span class="badge badge-pill 
                                    @if(strtolower($penjualan->status_order) == 'selesai') badge-success
                                    @elseif(strtolower($penjualan->status_order) == 'diproses') badge-info
                                    @elseif(strtolower($penjualan->status_order) == 'menunggu konfirmasi') badge-warning
                                    @else badge-secondary @endif"
                                    style="font-size:13px;
                                    @if(strtolower($penjualan->status_order) == 'selesai') background-color:#28a745 !important;color:#fff; @endif">
                                    {{ $penjualan->status_order }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><strong>Shipping Status:</strong></div>
                            <div class="col-6">
                                @if($penjualan->pengiriman)
                                    <span class="badge badge-pill
                                        @if($penjualan->pengiriman->status_kirim == 'Sedang Dikirim') badge-warning
                                        @elseif($penjualan->pengiriman->status_kirim == 'Tiba Di Tujuan') badge-success
                                        @else badge-secondary @endif"
                                        style="font-size:13px;
                                        @if($penjualan->pengiriman->status_kirim == 'Tiba Di Tujuan') background-color:#28a745 !important;color:#fff; @endif">
                                        {{ $penjualan->pengiriman->status_kirim }}
                                    </span>
                                @else
                                    <span class="badge badge-pill badge-secondary" style="font-size:13px;">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><strong>Courier:</strong></div>
                            <div class="col-6">{{ $penjualan->pengiriman->nama_kurir ?? '-' }} ({{ $penjualan->pengiriman->telpon_kurir ?? '-' }})</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-6"><strong>Total Order Price:</strong></div>
                            <div class="col-6 text-danger font-weight-bold">Rp{{ number_format($penjualan->total_bayar,0,',','.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom-0">
                        <strong>Produk</strong>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Products</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($penjualan->detailPenjualans as $detail)
                                <tr>
                                    <td>{{ $detail->obat->nama_obat ?? '-' }}</td>
                                    <td>{{ $detail->jumlah_beli }}</td>
                                    <td>Rp{{ number_format($detail->harga_beli,0,',','.') }}</td>
                                    <td>Rp{{ number_format($detail->subtotal,0,',','.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($penjualan->pengiriman && $penjualan->pengiriman->bukti_foto)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom-0">
                        <strong>Bukti Foto</strong>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ Storage::url($penjualan->pengiriman->bukti_foto) }}" alt="Bukti" class="rounded shadow-sm" style="max-width:300px;max-height:300px;object-fit:cover;">
                    </div>
                </div>
                @endif
                <div class="mt-3">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <a href="{{ route('fe.pesanan') }}" class="btn btn-info btn-sm mb-1 mb-md-0" style="border-radius:20px;">
                            <i class="fa fa-arrow-left"></i> Back to My Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .section-title { margin-bottom: 24px; font-weight: 700; color: #222; }
    .badge, .badge-pill { font-size: 0.95em; font-weight: 500; letter-spacing: 0.02em; }
    .table td, .table th { vertical-align: middle; }
    .card { margin-bottom: 30px; border-radius: 12px; }
    .card-body { border-radius: 12px; }
    .btn-info { border-radius: 20px; }
    .shadow-sm { box-shadow: 0 2px 8px rgba(0,0,0,0.07) !important; }
    .thead-light th { background: #f7f7f7; }
</style>
@endpush

@section('footer')
    @include('fe.footer')
@endsection
