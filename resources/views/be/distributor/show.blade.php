@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h5 class="font-weight-bold text-primary mb-3">
                                        <i class="fas fa-user-tie me-2"></i>Informasi Distributor
                                    </h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="35%" class="bg-light">Nama Distributor</th>
                                            <td>{{ $distributor->nama_distributor }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Telepon</th>
                                            <td>{{ $distributor->telepon }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Alamat</th>
                                            <td>{{ $distributor->alamat }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                @if(auth()->user()->role == 'pemilik')
                <a href="{{ route('daftardistributor.index') }}" class="btn btn-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                </a>
                @endif
                
                @if(auth()->user()->role !== 'pemilik')
                <a href="{{ route('distributor.index') }}" class="btn btn-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                </a>
                <div>
                    <a href="{{ route('distributor.edit', $distributor->id) }}" class="btn btn-warning rounded-pill me-2">
                        <i class="fas fa-edit me-2"></i> Edit Data
                    </a>
                    <form action="{{ route('distributor.destroy', $distributor->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('Hapus data distributor ini?')">
                            <i class="fas fa-trash-alt me-2"></i> Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection