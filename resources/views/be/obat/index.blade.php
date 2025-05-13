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
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('obat.create') }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Obat
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>Jenis</th>
                                            <th>Harga</th>
                                            <th>Deskripsi</th>
                                            <th>Foto</th>
                                            <th>Stok</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($obats as $nmr => $data)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>{{ $data->nama_obat }}</td>
                                            <td>{{ $data->jenisObat->jenis ?? '-' }}</td>
                                            <td>Rp{{ number_format($data->harga_jual, 0, ',', '.') }}</td>
                                            <td>{{ Str::limit($data->deskripsi_obat, 30) }}</td>
                                            <td>
                                                @if($data->foto1)
                                                    <img src="{{ asset('storage/' . $data->foto1) }}" width="50" class="rounded">
                                                @endif
                                            </td>
                                            <td>{{ $data->stok }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('obat.show', $data->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    <a href="{{ route('obat.edit', $data->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('obat.destroy', $data->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Hapus obat ini?')">
                                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
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