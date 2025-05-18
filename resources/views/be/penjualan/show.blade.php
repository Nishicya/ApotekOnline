@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Penjualan #{{ $penjualan->id }}</h6>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Tanggal Penjualan:</strong></label>
                        <p>{{ $penjualan->tgl_penjualan }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Pelanggan:</strong></label>
                        <p>
                            {{ $penjualan->pelanggan->nama_pelanggan }}<br>
                            <small class="text-muted">{{ $penjualan->pelanggan->email }}</small>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Metode Pembayaran:</strong></label>
                        <p>
                            {{ $penjualan->metodeBayar->metode_pembayaran }}
                        </p>
                    </div>
                    <div class="form-group">
                        <label><strong>Pengiriman:</strong></label>
                        <p>{{ $penjualan->jenisPengiriman->nama_ekspedisi }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Status:</strong></label>
                        <p>{{ $penjualan->status_order }}</p>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Ongkos Kirim:</strong></label>
                        <p>Rp {{ number_format($penjualan->ongkos_kirim, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Biaya Aplikasi:</strong></label>
                        <p>Rp {{ number_format($penjualan->biaya_app, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detailPenjualans as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->obat->nama_obat }}</td>
                            <td>{{ $detail->jumlah_beli }}</td>
                            <td>Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-right">Total</th>
                            <th>Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($penjualan->keterangan_status)
            <div class="form-group mt-4">
                <label><strong>Keterangan Status:</strong></label>
                <p>{{ $penjualan->keterangan_status }}</p>
            </div>
            @endif

            @if($penjualan->url_resep)
            <div class="form-group mt-4">
                <label><strong>Resep Dokter:</strong></label>
                <div>
                    <img src="{{ asset('storage/'.$penjualan->url_resep) }}" alt="Resep Dokter" style="max-width: 300px;" class="img-thumbnail">
                </div>
            </div>
            @endif

            <div class="mt-4">
                @if(auth()->user()->role == 'pemilik')
                <a href="{{ route('laporanpenjualan.manage') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Penjualan
                </a>
                @endif

                @if(auth()->user()->role !== 'pemilik')
                <a href="{{ route('penjualan.manage') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Penjualan
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection