@extends('fe.master')

@section('page_title', 'HEALTHIFY - Order Details')

@section('header')
    @include('fe.header')
@endsection

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h2 class="section-title" style="margin: 0; font-weight: 700; color: #222; display: flex; align-items: center; gap: 15px;">
                    Order Details
                    @if($penjualan->pengiriman)
                        <span class="badge" style="padding: 6px 12px; font-size: 12px; border-radius: 20px; font-weight: 600; margin: 0;
                            @if($penjualan->pengiriman->status_kirim == 'Tiba Di Tujuan') background-color: #28a745; color: white;
                            @elseif($penjualan->pengiriman->status_kirim == 'Sedang Dikirim') background-color: #FFC107; color: #333;
                            @else background-color: #6c757d; color: white; @endif">
                            {{ $penjualan->pengiriman->status_kirim }}
                        </span>
                    @endif
                    <span class="badge" style="background-color: #D10024; color: white; padding: 6px 12px; font-size: 12px; border-radius: 20px; margin: 0; font-weight: 600;">
                        {{ $penjualan->pengiriman->no_invoice ?? 'Pending' }}
                    </span>
                </h2>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <!-- Products Card - Left Side, Wider -->
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                            <div class="card-header" style="background-color: #D10024; color: white; border: none; padding: 20px;">
                                <h5 style="margin: 0; font-weight: 600;">Products Ordered</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0" style="font-size: 14px;">
                                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #eee;">
                                            <tr>
                                                <th style="padding: 15px; font-weight: 600; color: #333;">Product Name</th>
                                                <th style="padding: 15px; font-weight: 600; color: #333; text-align: center;">Qty</th>
                                                <th style="padding: 15px; font-weight: 600; color: #333; text-align: right;">Price</th>
                                                <th style="padding: 15px; font-weight: 600; color: #333; text-align: right;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($penjualan->detailPenjualans as $detail)
                                            <tr style="border-bottom: 1px solid #eee;">
                                                <td style="padding: 15px; color: #222; font-weight: 500;">{{ $detail->obat->nama_obat ?? '-' }}</td>
                                                <td style="padding: 15px; color: #666; text-align: center;">{{ $detail->jumlah_beli }}</td>
                                                <td style="padding: 15px; color: #666; text-align: right;">Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                                <td style="padding: 15px; color: #D10024; font-weight: 600; text-align: right;">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Information Card - Right Side, Narrower -->
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <div class="card-header" style="background-color: #D10024; color: white; border-radius: 12px 12px 0 0; border: none; padding: 20px;">
                                <h5 style="margin: 0; font-weight: 600;">Order Information</h5>
                            </div>
                            <div class="card-body" style="padding: 25px;">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Order Date</p>
                                        <p style="color: #222; margin: 5px 0 0 0; font-weight: 600; font-size: 15px;">{{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d M Y') }}</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                                        <span class="badge" style="margin-top: 5px; padding: 6px 12px; font-size: 12px; border-radius: 20px;
                                            @if(strtolower($penjualan->status_order) == 'selesai') background-color: #28a745; color: white;
                                            @elseif(strtolower($penjualan->status_order) == 'diproses') background-color: #17a2b8; color: white;
                                            @elseif(strtolower($penjualan->status_order) == 'menunggu konfirmasi') background-color: #ffc107; color: #333;
                                            @else background-color: #6c757d; color: white; @endif">
                                            {{ $penjualan->status_order }}
                                        </span>
                                    </div>
                                </div>

                                <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Estimated Arrival</p>
                                        <p style="color: #222; margin: 5px 0 0 0; font-weight: 600; font-size: 15px;">
                                            @if($penjualan->pengiriman && $penjualan->pengiriman->tgl_tiba)
                                                {{ \Carbon\Carbon::parse($penjualan->pengiriman->tgl_tiba)->format('d M Y') }}
                                            @elseif($penjualan->pengiriman && $penjualan->pengiriman->tgl_kirim)
                                                {{ \Carbon\Carbon::parse($penjualan->pengiriman->tgl_kirim)->addDays(2)->format('d M Y') }}
                                            @else
                                                <span style="color: #999;">On Process</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Shipping Status</p>
                                        @if($penjualan->pengiriman)
                                            <span class="badge" style="margin-top: 5px; padding: 6px 12px; font-size: 12px; border-radius: 20px;
                                                @if($penjualan->pengiriman->status_kirim == 'Tiba Di Tujuan') background-color: #28a745; color: white;
                                                @elseif($penjualan->pengiriman->status_kirim == 'Sedang Dikirim') background-color: #FFC107; color: #333;
                                                @else background-color: #6c757d; color: white; @endif">
                                                {{ $penjualan->pengiriman->status_kirim }}
                                            </span>
                                        @else
                                            <span class="badge" style="margin-top: 5px; padding: 6px 12px; font-size: 12px; border-radius: 20px; background-color: #6c757d; color: white;">
                                                Waiting Confirmation
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">

                                @if($penjualan->pengiriman)
                                <div class="row">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Courier</p>
                                        <p style="color: #222; margin: 5px 0 0 0; font-weight: 600; font-size: 15px;">{{ $penjualan->pengiriman->nama_kurir ?? '-' }}</p>
                                        <p style="color: #999; margin: 3px 0 0 0; font-size: 13px;">{{ $penjualan->pengiriman->telpon_kurir ?? '-' }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Price Summary - Full Width Below -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <div class="card-body" style="padding: 25px;">
                                <div class="row mb-3" style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
                                    <div class="col-6">
                                        <p style="color: #666; margin: 0; font-size: 14px;">Subtotal</p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <p style="color: #222; margin: 0; font-weight: 600; font-size: 14px;">Rp{{ number_format($penjualan->detailPenjualans->sum('subtotal'), 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="row mb-3" style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
                                    <div class="col-6">
                                        <p style="color: #666; margin: 0; font-size: 14px;">Shipping Cost</p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <p style="color: #222; margin: 0; font-weight: 600; font-size: 14px;">Rp{{ number_format($penjualan->ongkos_kirim, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="row mb-3" style="border-bottom: 1px solid #eee; padding-bottom: 15px;">
                                    <div class="col-6">
                                        <p style="color: #666; margin: 0; font-size: 14px;">App Fee</p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <p style="color: #222; margin: 0; font-weight: 600; font-size: 14px;">Rp{{ number_format($penjualan->biaya_app, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="row" style="padding-top: 10px;">
                                    <div class="col-6">
                                        <p style="color: #222; margin: 0; font-size: 15px; font-weight: 700; text-transform: uppercase;">Total</p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <p style="color: #D10024; margin: 0; font-weight: 700; font-size: 20px;">Rp{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Proof Photo -->
                @if($penjualan->pengiriman && $penjualan->pengiriman->bukti_foto)
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header" style="background-color: #D10024; color: white; border: none; padding: 20px;">
                        <h5 style="margin: 0; font-weight: 600;">Delivery Proof</h5>
                    </div>
                    <div class="card-body text-center" style="padding: 30px;">
                        <img src="{{ Storage::url($penjualan->pengiriman->bukti_foto) }}" alt="Proof" 
                            class="rounded shadow-sm" style="max-width: 100%; height: auto; max-height: 400px; object-fit: cover;">
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="mb-4">
                    <a href="{{ route('fe.pesanan') }}" class="btn btn-lg" style="background-color: #D10024; color: white; border: none; border-radius: 25px; width: 100%; font-weight: 600; padding: 12px 20px;">
                        <i class="fa fa-arrow-left" style="margin-right: 8px;"></i>Back to My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .section-title {
        font-weight: 700;
        color: #222;
        margin-bottom: 10px;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    }

    .badge {
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .table {
        margin-bottom: 0;
    }

    .table td {
        vertical-align: middle;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(209, 16, 36, 0.3);
    }

    .text-right {
        text-align: right;
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .d-flex {
            flex-direction: column !important;
            gap: 15px;
        }

        .card-body {
            padding: 15px !important;
        }

        .table {
            font-size: 12px;
        }

        .table th, .table td {
            padding: 10px !important;
        }

        .card-header {
            padding: 15px !important;
        }

        .card-header h5 {
            font-size: 16px !important;
        }

        .badge {
            font-size: 12px !important;
            padding: 6px 12px !important;
        }

        .col-md-7, .col-md-5, .col-md-8, .col-md-12 {
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-bottom: 15px;
        }
    }

    @media (max-width: 576px) {
        .col-6 {
            font-size: 12px;
        }

        .section-title {
            font-size: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }
</style>
@endpush

@section('footer')
    @include('fe.footer')
@endsection
