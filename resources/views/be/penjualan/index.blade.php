@extends('be.master')

@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                        <a href="{{ route('penjualan.create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
                    </div>
                    
                    <p class="card-description mb-3">
                        Daftar Data Penjualan
                    </p>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    <div class="table-responsive pt-3">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Metode Bayar</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($penjualan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tgl_penjualan)->translatedFormat('d M Y H:i') }}</td>
                                    <td>{{ $item->pelanggan->nama ?? '-' }}</td>
                                    <td>{{ $item->metodeBayar->nama_metode ?? '-' }}</td>
                                    <td>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-pill 
                                            @if($item->status_order == 'selesai') badge-success
                                            @elseif($item->status_order == 'diproses') badge-warning
                                            @else badge-secondary @endif">
                                            {{ ucfirst($item->status_order) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                            <a href="{{ route('penjualan.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('penjualan.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data ini?')" title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
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
@endsection

@section('styles')
    <style>
        /* Custom styles for the table */
        .table-responsive {
            border-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .table thead th {
            white-space: nowrap;
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        /* For the status badges */
        .badge-pill {
            padding: 5px 10px;
            font-size: 12px;
        }
    </style>
@endsection

