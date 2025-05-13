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
                            <form method="POST" action="{{ route('obat.update', $obat->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="nama_obat">Nama Obat</label>
                                    <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="id_jenis">Jenis Obat</label>
                                    <select class="form-control" id="id_jenis" name="id_jenis" required>
                                        <option value="">Pilih Jenis Obat</option>
                                        @foreach($jenisObats as $jenis)
                                            <option value="{{ $jenis->id }}" {{ $obat->id_jenis == $jenis->id ? 'selected' : '' }}>
                                                {{ $jenis->jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual</label>
                                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="{{ old('harga_jual', $obat->harga_jual) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="deskripsi_obat">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi_obat" name="deskripsi_obat" rows="3">{{ old('deskripsi_obat', $obat->deskripsi_obat) }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="stok">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" value="{{ old('stok', $obat->stok) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Foto Obat</label>
                                    <div class="row">
                                        <!-- Foto 1 -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="foto1">Foto 1</label>
                                                <input type="file" class="form-control" id="foto1" name="foto1">
                                                @if($obat->foto1)
                                                    <div class="mt-2 position-relative">
                                                        <img src="{{ asset('storage/' . $obat->foto1) }}" width="100" class="img-thumbnail">
                                                        <div class="form-check mt-2">
                                                            <input type="checkbox" class="form-check-input" id="delete_foto1" name="delete_foto1" value="1">
                                                            <label class="form-check-label text-danger" for="delete_foto1">
                                                                <i class="fas fa-trash-alt"></i> Hapus Foto
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Foto 2 -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="foto2">Foto 2</label>
                                                <input type="file" class="form-control" id="foto2" name="foto2">
                                                @if($obat->foto2)
                                                    <div class="mt-2 position-relative">
                                                        <img src="{{ asset('storage/' . $obat->foto2) }}" width="100" class="img-thumbnail">
                                                        <div class="form-check mt-2">
                                                            <input type="checkbox" class="form-check-input" id="delete_foto2" name="delete_foto2" value="1">
                                                            <label class="form-check-label text-danger" for="delete_foto2">
                                                                <i class="fas fa-trash-alt"></i> Hapus Foto
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Foto 3 -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="foto3">Foto 3</label>
                                                <input type="file" class="form-control" id="foto3" name="foto3">
                                                @if($obat->foto3)
                                                    <div class="mt-2 position-relative">
                                                        <img src="{{ asset('storage/' . $obat->foto3) }}" width="100" class="img-thumbnail">
                                                        <div class="form-check mt-2">
                                                            <input type="checkbox" class="form-check-input" id="delete_foto3" name="delete_foto3" value="1">
                                                            <label class="form-check-label text-danger" for="delete_foto3">
                                                                <i class="fas fa-trash-alt"></i> Hapus Foto
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                <a href="{{ route('obat.manage') }}" class="btn btn-light">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection