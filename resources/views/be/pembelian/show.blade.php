@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Pembelian</h6>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>No. Nota:</strong></label>
                        <p>{{ $pembelian->no_nota }}</p>
                    </div>
                    <div class="form-group">
                        <label><strong>Tanggal Pembelian:</strong></label>
                        <p>{{ $pembelian->tgl_pembelian }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Distributor:</strong></label>
                        @if($pembelian->relationLoaded('distributor') && $pembelian->distributor)
                            <p>{{ $pembelian->distributor->nama_distributor }}</p>
                            @if($pembelian->distributor->telepon)
                                <p><small>Telepon: {{ $pembelian->distributor->telepon }}</small></p>
                            @endif
                        @else
                            <p class="text-warning">Distributor tidak tersedia</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label><strong>Total Bayar:</strong></label>
                        <p>Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Jumlah Beli</th>
                            <th>Harga Beli</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pembelian->detailPembelians as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->obat->nama_obat }}</td>
                            <td>{{ $detail->jumlah_beli }}</td>
                            <td>Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                @if(auth()->user()->role == 'pemilik')
                <a href="{{ route('laporanpembelian.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pembelian
                </a>
                @endif

                @if(auth()->user()->role !== 'pemilik')
                <a href="{{ route('pembelian.manage') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pembelian
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection