@extends('be.master')

@section('navbar')
@include('be.navbar')
@endsection

@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            <p class="card-description">Edit data pelanggan</p>

            <form class="forms-sample" method="POST" action="{{ route('pelanggan.update', $pelanggan->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_pelanggan">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" class="form-control @error('nama_pelanggan') is-invalid @enderror"
                           value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}">
                    @error('nama_pelanggan')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $pelanggan->email) }}">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password (Leave blank to keep current)</label>
                    <input type="password" class="form-control" id="password" name="password">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_hp">No HP</label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp', $pelanggan->no_hp) }}">
                    @error('no_hp')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Alamat 1 --}}
                <div class="form-group">
                    <label for="alamat1">Alamat 1</label>
                    <input type="text" name="alamat1" class="form-control" value="{{ old('alamat1', $pelanggan->alamat1) }}">
                </div>
                <div class="form-group">
                    <label for="kota1">Kota 1</label>
                    <input type="text" name="kota1" class="form-control" value="{{ old('kota1', $pelanggan->kota1) }}">
                </div>
                <div class="form-group">
                    <label for="propinsi1">Provinsi 1</label>
                    <input type="text" name="propinsi1" class="form-control" value="{{ old('propinsi1', $pelanggan->propinsi1) }}">
                </div>
                <div class="form-group">
                    <label for="kodepos1">Kodepos 1</label>
                    <input type="text" name="kodepos1" class="form-control" value="{{ old('kodepos1', $pelanggan->kodepos1) }}">
                </div>

                {{-- Alamat 2 --}}
                <div class="form-group">
                    <label for="alamat2">Alamat 2</label>
                    <input type="text" name="alamat2" class="form-control" value="{{ old('alamat2', $pelanggan->alamat2) }}">
                </div>
                <div class="form-group">
                    <label for="kota2">Kota 2</label>
                    <input type="text" name="kota2" class="form-control" value="{{ old('kota2', $pelanggan->kota2) }}">
                </div>
                <div class="form-group">
                    <label for="propinsi2">Provinsi 2</label>
                    <input type="text" name="propinsi2" class="form-control" value="{{ old('propinsi2', $pelanggan->propinsi2) }}">
                </div>
                <div class="form-group">
                    <label for="kodepos2">Kodepos 2</label>
                    <input type="text" name="kodepos2" class="form-control" value="{{ old('kodepos2', $pelanggan->kodepos2) }}">
                </div>

                {{-- Alamat 3 --}}
                <div class="form-group">
                    <label for="alamat3">Alamat 3</label>
                    <input type="text" name="alamat3" class="form-control" value="{{ old('alamat3', $pelanggan->alamat3) }}">
                </div>
                <div class="form-group">
                    <label for="kota3">Kota 3</label>
                    <input type="text" name="kota3" class="form-control" value="{{ old('kota3', $pelanggan->kota3) }}">
                </div>
                <div class="form-group">
                    <label for="propinsi3">Provinsi 3</label>
                    <input type="text" name="propinsi3" class="form-control" value="{{ old('propinsi3', $pelanggan->propinsi3) }}">
                </div>
                <div class="form-group">
                    <label for="kodepos3">Kodepos 3</label>
                    <input type="text" name="kodepos3" class="form-control" value="{{ old('kodepos3', $pelanggan->kodepos3) }}">
                </div>

                {{-- Upload Foto --}}
                <div class="form-group">
                    <label for="foto">Foto Profil</label><br>
                    @if ($pelanggan->foto)
                        <img src="{{ asset('storage/' . $pelanggan->foto) }}" alt="Foto" height="80"><br><br>
                    @endif
                    <input type="file" name="foto" class="form-control">
                </div>

                {{-- Upload KTP --}}
                <div class="form-group">
                    <label for="url_ktp">Foto KTP</label><br>
                    @if ($pelanggan->url_ktp)
                        <img src="{{ asset('storage/' . $pelanggan->url_ktp) }}" alt="KTP" height="80"><br><br>
                    @endif
                    <input type="file" name="url_ktp" class="form-control">
                </div>

                <button type="submit" class="btn btn-success mr-2">
                    <i class="mdi mdi-content-save"></i> Update
                </button>
                <a href="{{ route('pelanggan.manage') }}" class="btn btn-light">
                    <i class="mdi mdi-close"></i> Batal
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
