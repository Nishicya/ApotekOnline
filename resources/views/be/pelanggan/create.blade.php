@extends('be.master')

@section('navbar')
@include('be.navbar')
@endsection

@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            <p class="card-description">Create new pelanggan</p>

            <form class="forms-sample" method="POST" action="{{ route('pelanggan.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" class="form-control @error('nama_pelanggan') is-invalid @enderror"
                           id="nama_pelanggan" placeholder="Nama Lengkap" value="{{ old('nama_pelanggan') }}">
                    @error('nama_pelanggan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" placeholder="Email" value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_hp">No HP</label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                           id="no_hp" placeholder="08xxx" value="{{ old('no_hp') }}">
                    @error('no_hp')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" placeholder="Password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           id="password_confirmation" placeholder="Konfirmasi Password">
                </div>

                {{-- Alamat 1 --}}
                <div class="form-group">
                    <label for="alamat1">Alamat 1</label>
                    <input type="text" name="alamat1" class="form-control" value="{{ old('alamat1') }}">
                </div>
                <div class="form-group">
                    <label for="kota1">Kota 1</label>
                    <input type="text" name="kota1" class="form-control" value="{{ old('kota1') }}">
                </div>
                <div class="form-group">
                    <label for="propinsi1">Provinsi 1</label>
                    <input type="text" name="propinsi1" class="form-control" value="{{ old('propinsi1') }}">
                </div>
                <div class="form-group">
                    <label for="kodepos1">Kodepos 1</label>
                    <input type="text" name="kodepos1" class="form-control" value="{{ old('kodepos1') }}">
                </div>

                {{-- Alamat 2 --}}
                <div class="form-group">
                    <label for="alamat2">Alamat 2</label>
                    <input type="text" name="alamat2" class="form-control" value="{{ old('alamat2') }}">
                </div>
                <div class="form-group">
                    <label for="kota2">Kota 2</label>
                    <input type="text" name="kota2" class="form-control" value="{{ old('kota2') }}">
                </div>
                <div class="form-group">
                    <label for="propinsi2">Provinsi 2</label>
                    <input type="text" name="propinsi2" class="form-control" value="{{ old('propinsi2') }}">
                </div>
                <div class="form-group">
                    <label for="kodepos2">Kodepos 2</label>
                    <input type="text" name="kodepos2" class="form-control" value="{{ old('kodepos2') }}">
                </div>

                {{-- Alamat 3 --}}
                <div class="form-group">
                    <label for="alamat3">Alamat 3</label>
                    <input type="text" name="alamat3" class="form-control" value="{{ old('alamat3') }}">
                </div>
                <div class="form-group">
                    <label for="kota3">Kota 3</label>
                    <input type="text" name="kota3" class="form-control" value="{{ old('kota3') }}">
                </div>
                <div class="form-group">
                    <label for="propinsi3">Provinsi 3</label>
                    <input type="text" name="propinsi3" class="form-control" value="{{ old('propinsi3') }}">
                </div>
                <div class="form-group">
                    <label for="kodepos3">Kodepos 3</label>
                    <input type="text" name="kodepos3" class="form-control" value="{{ old('kodepos3') }}">
                </div>

                {{-- Upload Foto --}}
                <div class="form-group">
                    <label for="foto">Foto Profil</label>
                    <input type="file" name="foto" class="form-control">
                </div>

                {{-- Upload KTP --}}
                <div class="form-group">
                    <label for="url_ktp">Foto KTP</label>
                    <input type="file" name="url_ktp" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary mr-2">
                    <i class="mdi mdi-content-save"></i> Simpan
                </button>
                <a href="{{ route('pelanggan.manage') }}" class="btn btn-light">
                    <i class="mdi mdi-close"></i> Batal
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
