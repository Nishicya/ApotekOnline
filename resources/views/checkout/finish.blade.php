@extends('fe.master')

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
                <h2 class="section-title" style="margin: 0 0 30px 0; font-weight: 700; color: #222;">Menunggu Konfirmasi</h2>
            </div>

            <!-- Confirmation Card -->
            <div class="col-md-12" style="margin-bottom: 35px;">
                <div class="card border-0 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #D10024 0%, #a0001a 100%); color: white; overflow: hidden;">
                    <div class="card-body" style="padding: 40px; text-align: center;">
                        <i class="fa fa-check-circle" style="font-size: 60px; margin-bottom: 20px; color: #fff;"></i>
                        <h3 style="margin: 0 0 10px 0; font-weight: 700; font-size: 28px;">Pesanan Berhasil Dibuat!</h3>
                        <p style="margin: 0; font-size: 16px; opacity: 0.95;">Silahkan cek detail pesanan Anda di bawah ini</p>
                    </div>
                </div>
            </div>

            <!-- Order Details and Shipping Info -->
            <div class="col-md-12 mb-5">
                <div class="row">
                    <!-- Products Card - Left Side -->
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
                            <div class="card-header" style="background-color: #D10024; color: white; border: none; padding: 20px;">
                                <h5 style="margin: 0; font-weight: 600;">Detail Pesanan</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0" style="font-size: 14px;">
                                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #eee;">
                                            <tr>
                                                <th style="padding: 15px; font-weight: 600; color: #333;">Produk</th>
                                                <th style="padding: 15px; font-weight: 600; color: #333; text-align: center;">Qty</th>
                                                <th style="padding: 15px; font-weight: 600; color: #333; text-align: right;">Harga</th>
                                                <th style="padding: 15px; font-weight: 600; color: #333; text-align: right;">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($keranjangItems) && $keranjangItems->count() > 0)
                                                @foreach($keranjangItems as $item)
                                                <tr style="border-bottom: 1px solid #eee;">
                                                    <td style="padding: 15px; color: #222; font-weight: 500;">{{ $item->obat->nama_obat ?? 'Produk tidak ditemukan' }}</td>
                                                    <td style="padding: 15px; color: #666; text-align: center;">{{ $item->jumlah_beli }}</td>
                                                    <td style="padding: 15px; color: #666; text-align: right;">Rp{{ number_format($item->harga_beli ?? 0, 0, ',', '.') }}</td>
                                                    <td style="padding: 15px; color: #D10024; font-weight: 600; text-align: right;">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                                @endforeach
                                                <!-- Shipping Cost Row -->
                                                <tr style="border-bottom: 1px solid #eee; background-color: #f9f9f9;">
                                                    <td colspan="3" style="padding: 15px; text-align: right; font-weight: 600; color: #333;">Ongkos Kirim:</td>
                                                    <td style="padding: 15px; color: #666; text-align: right;">Rp{{ number_format($penjualan->ongkos_kirim ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <!-- App Fee Row -->
                                                <tr style="border-bottom: 2px solid #eee; background-color: #f9f9f9;">
                                                    <td colspan="3" style="padding: 15px; text-align: right; font-weight: 600; color: #333;">Biaya Aplikasi:</td>
                                                    <td style="padding: 15px; color: #666; text-align: right;">Rp{{ number_format($penjualan->biaya_app ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                                <!-- Total Row -->
                                                <tr style="background-color: #f9f9f9;">
                                                    <td colspan="3" style="padding: 15px; text-align: right; font-weight: 700; color: #333; font-size: 16px;">TOTAL:</td>
                                                    <td style="padding: 15px; color: #D10024; font-weight: 700; text-align: right; font-size: 16px;">Rp{{ number_format($penjualan->total_bayar ?? 0, 0, ',', '.') }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="4" style="padding: 20px; text-align: center; color: #666;">Tidak ada data item ditemukan</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping & Payment Info Card - Right Side -->
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                            <div class="card-header" style="background-color: #D10024; color: white; border: none; padding: 20px;">
                                <h5 style="margin: 0; font-weight: 600;">Informasi Pengiriman & Pembayaran</h5>
                            </div>
                            <div class="card-body" style="padding: 25px;">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Alamat Pengiriman</p>
                                        <p style="color: #222; margin: 5px 0 0 0; font-weight: 600; font-size: 14px;">
                                            @if(isset($penjualan) && $penjualan)
                                                {{ $penjualan->alamat_pengiriman ?? session('checkout_alamat') ?? 'Belum dipilih' }}
                                            @elseif(old('alamat_pengiriman'))
                                                {{ old('alamat_pengiriman') }}
                                            @else
                                                {{ session('checkout_alamat') ?? 'Belum dipilih' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Metode Pembayaran</p>
                                        <p style="color: #222; margin: 5px 0 0 0; font-weight: 600; font-size: 14px;">
                                            @if(isset($penjualan) && $penjualan && $penjualan->metodeBayar)
                                                {{ $penjualan->metodeBayar->metode_pembayaran ?? session('checkout_metode') ?? 'Belum dipilih' }}
                                            @elseif(old('id_metode_bayar') && isset($metodeBayar))
                                                @php
                                                    $metode = $metodeBayar->where('id', old('id_metode_bayar'))->first();
                                                @endphp
                                                {{ $metode->metode_pembayaran ?? session('checkout_metode') ?? 'Belum dipilih' }}
                                            @else
                                                {{ session('checkout_metode') ?? 'Belum dipilih' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Pengiriman</p>
                                        <p style="color: #222; margin: 5px 0 0 0; font-weight: 600; font-size: 14px;">
                                            @if(isset($penjualan) && $penjualan && $penjualan->jenisPengiriman)
                                                {{ $penjualan->jenisPengiriman->jenis_kirim ?? session('checkout_jenis_kirim') ?? 'Belum dipilih' }}
                                            @elseif(old('id_jenis_kirim') && isset($jenisPengiriman))
                                                @php
                                                    $jenis = $jenisPengiriman->where('id', old('id_jenis_kirim'))->first();
                                                @endphp
                                                {{ $jenis->jenis_kirim ?? session('checkout_jenis_kirim') ?? 'Belum dipilih' }}
                                            @else
                                                {{ session('checkout_jenis_kirim') ?? 'Belum dipilih' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">

                                @if(session('checkout_catatan'))
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <p style="color: #666; margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Catatan</p>
                                        <p style="color: #222; margin: 5px 0 0 0; font-weight: 600; font-size: 14px;">{{ session('checkout_catatan') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('keranjang') }}" class="btn-action btn-kembali" style="display: block; text-align: center; padding: 12px 20px; border-radius: 25px; text-decoration: none; background-color: #ffffff; color: #D10024; border: 2px solid #D10024; font-weight: 600; transition: all 0.3s ease;">
                            <i class="fa fa-arrow-left" style="margin-right: 8px;"></i>Kembali ke Keranjang
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <button type="button" class="btn btn-lg btn-cancel-order" style="background-color: #dc3545; color: white; border: none; border-radius: 25px; width: 100%; font-weight: 600; padding: 12px 20px; display: block;" onclick="cancelOrder()">
                            <i class="fa fa-trash" style="margin-right: 8px;"></i>Batalkan Pesanan
                        </button>
                    </div>
                </div>

                <!-- Hidden form for cancel -->
                <form id="cancel-form" action="{{ route('pesanan.cancel') }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                    @if(isset($penjualan) && $penjualan)
                        <input type="hidden" name="penjualan_id" value="{{ $penjualan->id }}">
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    }

    /* Button hover effects */
    .btn-action {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }

    .btn-action.btn-kembali {
        background-color: #ffffff !important;
        color: #D10024 !important;
        border: 2px solid #D10024 !important;
    }

    .btn-action.btn-kembali:hover {
        background-color: #D10024 !important;
        color: white !important;
        transform: translateY(-4px) !important;
        box-shadow: 0 10px 25px rgba(209, 16, 36, 0.3) !important;
        border-color: #D10024 !important;
    }

    .btn-action.btn-batalkan {
        background-color: #D10024 !important;
        color: white !important;
        border: 2px solid #D10024 !important;
    }

    .btn-batalkan:hover {
        background-color: #D10024 !important;
        color: white !important;
    }

    .text-right {
        text-align: right;
    }

    .table {
        margin-bottom: 0;
    }

    .table td {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .card-body {
            padding: 15px !important;
        }

        .card-header {
            padding: 15px !important;
        }

        .card-header h5 {
            font-size: 16px !important;
        }

        .table {
            font-size: 12px;
        }

        .table th, .table td {
            padding: 10px !important;
        }

        .col-md-7, .col-md-5, .col-md-6, .col-md-12 {
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

@section('script')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.cancelOrder = function() {
            Swal.fire({
                title: 'Batalkan Pesanan?',
                text: 'Apakah Anda yakin ingin membatalkan pesanan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D10024',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancel-form').submit();
                }
            });
        };
    });
</script>
@endpush

@section('footer')
    @include('fe.footer')
@endsection