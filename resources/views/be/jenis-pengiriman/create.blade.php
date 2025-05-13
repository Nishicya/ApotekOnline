@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $title }}</h4>
                            <form method="POST" action="{{ route('jenis-pengiriman.store') }}" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="jenis_kirim">Jenis Kirim</label>
                                    <select class="form-control" id="jenis_kirim" name="jenis_kirim" required>
                                        <option value="">Pilih Jenis Kirim</option>
                                        @foreach($jenisKirimOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nama_ekspedisi">Nama Ekspedisi</label>
                                    <input type="text" class="form-control" id="nama_ekspedisi" name="nama_ekspedisi" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="logo_ekspedisi">Logo Ekspedisi</label>
                                    <input type="file" class="form-control" id="logo_ekspedisi" name="logo_ekspedisi" required>
                                    <small class="text-muted">Format: JPEG, PNG, JPG</small>
                                </div>
                                
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                <a href="{{ route('jenis-pengiriman.manage') }}" class="btn btn-light">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection