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
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h5>Informasi Obat</h5>
                                        <hr>
                                        <p><strong>Nama Obat:</strong> {{ $obat->nama_obat }}</p>
                                        <p><strong>Jenis Obat:</strong> {{ $obat->jenisObat->jenis ?? '-' }}</p>
                                        <p><strong>Harga:</strong> Rp{{ number_format($obat->harga_jual, 0, ',', '.') }}</p>
                                        <p><strong>Stok:</strong> {{ $obat->stok }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h5>Foto Obat</h5>
                                        <hr>
                                        <div class="row">
                                            @if($obat->foto1)
                                            <div class="col-md-4 mb-3">
                                                <img src="{{ asset('storage/' . $obat->foto1) }}" class="img-fluid rounded">
                                            </div>
                                            @endif
                                            @if($obat->foto2)
                                            <div class="col-md-4 mb-3">
                                                <img src="{{ asset('storage/' . $obat->foto2) }}" class="img-fluid rounded">
                                            </div>
                                            @endif
                                            @if($obat->foto3)
                                            <div class="col-md-4 mb-3">
                                                <img src="{{ asset('storage/' . $obat->foto3) }}" class="img-fluid rounded">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <h5>Deskripsi Obat</h5>
                                <hr>
                                <p>{{ $obat->deskripsi_obat }}</p>
                            </div>
                            
                            <div class="d-flex justify-content-start">
                                <a href="{{ route('obat.manage') }}" class="btn btn-light rounded-pill me-2">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-primary rounded-pill me-2">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection