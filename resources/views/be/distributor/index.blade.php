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
                                <a href="{{ route('distributor.create') }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Distributor
                                </a>
                            </div>
                            @endif
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Distributor</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($distributors as $nmr => $data)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>{{ $data->nama_distributor }}</td>
                                            <td>{{ $data->telepon }}</td>
                                            <td>{{ Str::limit($data->alamat, 30) }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(auth()->user()->role == 'pemilik')
                                                    <a href="{{ route('daftardistributor.show', $data->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    @endif
                                                    
                                                    @if(auth()->user()->role !== 'pemilik')
                                                    <a href="{{ route('distributor.show', $data->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    <a href="{{ route('distributor.edit', $data->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('distributor.destroy', $data->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Hapus distributor ini?')">
                                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                    @endif
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