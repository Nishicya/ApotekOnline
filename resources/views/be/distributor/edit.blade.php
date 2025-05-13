@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            <p class="card-description">Form Edit Distributor</p>

            <form class="forms-sample" action="{{ route('distributor.update', $distributor->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_distributor">Nama Distributor</label>
                    <input type="text" class="form-control @error('nama_distributor') is-invalid @enderror" 
                           id="nama_distributor" name="nama_distributor" 
                           value="{{ old('nama_distributor', $distributor->nama_distributor) }}">
                    @error('nama_distributor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                           id="telepon" name="telepon" 
                           value="{{ old('telepon', $distributor->telepon) }}">
                    @error('telepon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control @error('alamat') is-invalid @enderror" 
                              id="alamat" name="alamat" rows="4">{{ old('alamat', $distributor->alamat) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mr-2">Simpan Perubahan</button>
                <a href="{{ route('distributor.index') }}" class="btn btn-light">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection