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
                            <h4 class="card-title">Medicine Type Detail</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <a href="{{ route('jenis-obat.manage') }}" class="btn btn-secondary rounded-pill">
                                        <i class="fas fa-arrow-left me-2"></i>Back to List
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    @if($jenisObat->image_url)
                                        <img src="{{ asset('storage/' . $jenisObat->image_url) }}" class="img-fluid rounded">
                                    @else
                                        <div class="text-center py-4 bg-light rounded">
                                            <i class="fas fa-image fa-5x text-muted"></i>
                                            <p class="mt-2">No Image Available</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h3>{{ $jenisObat->jenis }}</h3>
                                    <hr>
                                    <h5>Description:</h5>
                                    <p>{{ $jenisObat->deskripsi_jenis ?? '-' }}</p>
                                    <div class="mt-4">
                                        <a href="{{ route('jenis-obat.edit', $jenisObat->id) }}" class="btn btn-primary rounded-pill me-2">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('jenis-obat.destroy', $jenisObat->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger rounded-pill" onclick="return confirm('Delete this medicine type?')">
                                                <i class="fas fa-trash-alt me-1"></i> Delete
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
    </div>
</div>
@endsection