@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            <div class="d-flex justify-content-between mb-3">
                <p class="card-description">
                    Daftar Data Pengiriman
                </p>
                <a href="{{ route('pengiriman.create') }}" class="btn btn-primary">Tambah Data</a>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Invoice</th>
                            <th>Penjualan</th>
                            <th>Tanggal Kirim</th>
                            <th>Tanggal Tiba</th>
                            <th>Status</th>
                            <th>Kurir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengiriman as $item)
                        <tr>
                            <td>{{ $item->no_invoice }}</td>
                            <td>Penjualan #{{ $item->penjualan->id }}</td>
                            <td>{{ Carbon\Carbon::parse($item->tgl_kirim)->format('d/m/Y H:i') }}</td>
                            <td>{{ $item->tgl_tiba ? Carbon\Carbon::parse($item->tgl_tiba)->format('d/m/Y H:i') : '-' }}</td>
                            <td>
                                <span class="badge 
                                    @if($item->status_kirim == 'Sedang Dikirim') badge-warning
                                    @else badge-success @endif">
                                    {{ $item->status_kirim }}
                                </span>
                            </td>
                            <td>{{ $item->nama_kurir }} ({{ $item->telpon_kurir }})</td>
                            <td>
                                <a href="{{ route('pengiriman.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('pengiriman.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection