@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }} - #{{ $penjualan->id }}</h4>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d/m/Y H:i') }}</p>
            <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? '-' }}</p>
            <p><strong>Metode Bayar:</strong> {{ $penjualan->metodeBayar->nama ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($penjualan->status_order) }}</p>
            <p><strong>Total Bayar:</strong> Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</p>
            <hr>
            <h5>Detail Produk</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($penjualan->detailPenjualans as $detail)
                        <tr>
                            <td>{{ $detail->obat->nama ?? '-' }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection
