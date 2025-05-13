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
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Informasi Dasar</h5>
                                            <hr>
                                            <div class="mb-3">
                                                <label class="font-weight-bold">Jenis Pengiriman:</label>
                                                <p>{{ $jenisLabels[$jenisPengiriman->jenis_kirim] ?? $jenisPengiriman->jenis_kirim }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="font-weight-bold">Nama Ekspedisi:</label>
                                                <p>{{ $jenisPengiriman->nama_ekspedisi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Logo Ekspedisi</h5>
                                            <hr>
                                            @if($jenisPengiriman->logo_ekspedisi)
                                                <img src="{{ asset('storage/' . $jenisPengiriman->logo_ekspedisi) }}" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 200px; width: auto;">
                                            @else
                                                <div class="text-muted">Tidak ada logo</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-start">
                                <a href="{{ route('jenis-pengiriman.manage') }}" class="btn btn-light rounded-pill me-2">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <a href="{{ route('jenis-pengiriman.edit', $jenisPengiriman->id) }}" class="btn btn-primary rounded-pill me-2">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <form action="{{ route('jenis-pengiriman.destroy', $jenisPengiriman->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('Hapus jenis pengiriman ini?')">
                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection