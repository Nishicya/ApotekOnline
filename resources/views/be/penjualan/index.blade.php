@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $title }}</h4>
                            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'kasir')
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('penjualan.create') }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Penjualan
                                </a>
                            </div>
                            @else
                            @endif
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Metode Bayar</th>
                                            <th>Total Bayar</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penjualan as $nmr => $item)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_penjualan)->translatedFormat('d M Y H:i') }}</td>
                                            <td>{{ $item->pelanggan->nama_pelanggan ?? '-' }}</td>
                                            <td>
                                                @if($item->metodeBayar->url_logo ?? false)
                                                    <img src="{{ asset('storage/' . $item->metodeBayar->url_logo) }}" width="20" class="me-2">
                                                @endif
                                                {{ $item->metodeBayar->metode_pembayaran ?? '-' }}
                                            </td>
                                            <td>Rp{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge rounded-pill 
                                                    @if($item->status_order == 'selesai') bg-success
                                                    @elseif($item->status_order == 'diproses') bg-warning text-dark
                                                    @else bg-secondary @endif">
                                                    {{ ucfirst($item->status_order) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(auth()->user()->role == 'pemilik' || auth()->user()->role == 'kasir')
                                                        <a href="{{ route('laporanpenjualan.show', $item->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                    @else
                                                        <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                        <a href="{{ route('penjualan.edit', $item->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                        <form action="{{ route('penjualan.destroy', $item->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Hapus data penjualan ini?')">
                                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data penjualan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 500;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endsection