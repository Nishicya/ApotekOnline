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
                            @if(auth()->user()->role !== 'pemilik')
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('pengiriman.create') }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Pengiriman
                                </a>
                            </div>
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
                                            <th>No. Invoice</th>
                                            <th>Penjualan</th>
                                            <th>Tanggal Kirim</th>
                                            <th>Tanggal Tiba</th>
                                            <th>Status</th>
                                            <th>Kurir</th>
                                            @if(auth()->user()->role !== 'pemilik')
                                            <th>Aksi</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pengiriman as $nmr => $item)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>{{ $item->no_invoice }}</td>
                                            <td>Penjualan #{{ $item->penjualan->id }}</td>
                                            <td>{{ Carbon\Carbon::parse($item->tgl_kirim)->format('d/m/Y H:i') }}</td>
                                            <td>{{ $item->tgl_tiba ? Carbon\Carbon::parse($item->tgl_tiba)->format('d/m/Y H:i') : '-' }}</td>
                                            <td>
                                                <span class="badge rounded-pill 
                                                    @if($item->status_kirim == 'Sedang Dikirim') bg-warning text-dark
                                                    @else bg-success @endif">
                                                    {{ $item->status_kirim }}
                                                </span>
                                            </td>
                                            <td>{{ $item->nama_kurir }} ({{ $item->telpon_kurir }})</td>
                                            @if(auth()->user()->role !== 'pemilik')
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('pengiriman.edit', $item->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('pengiriman.destroy', $item->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">
                                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
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