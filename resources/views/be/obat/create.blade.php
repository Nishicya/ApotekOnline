@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            <p class="card-description">Form Kelola Obat</p>

            <form class="forms-sample" action="{{ isset($obat) ? route('obat.update', $obat->id) : route('obat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($obat))
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="nama_obat">Nama Obat</label>
                    <input type="text" class="form-control" id="nama_obat" name="nama_obat" placeholder="Nama" value="{{ old('nama_obat', $obat->nama_obat ?? '') }}">
                    @error('nama_obat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id_jenis">Jenis Obat</label>
                    <select class="form-control" id="id_jenis" name="id_jenis">
                        <option disabled selected>Pilih Jenis</option>
                        @foreach($jenisObats as $jenis)
                            <option value="{{ $jenis->id }} {{ (old('id_jenis', $obat->id_jenis ?? '') == $jenis->id) ? 'selected' : '' }}" >
                                {{ $jenis->jenis }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_jenis')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" placeholder="Harga" value="{{ old('harga_jual', $obat->harga_jual ?? '') }}">
                    @error('harga_jual')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi_obat">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi_obat" name="deskripsi_obat" rows="4" placeholder="Deskripsi">{{ old('deskripsi_obat', $obat->deskripsi_obat ?? '') }}</textarea>
                    @error('deskripsi_obat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" class="form-control" id="stok" name="stok" placeholder="Stok" value="{{ old('stok', $obat->stok ?? '') }}">
                    @error('stok')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                @foreach(['foto1', 'foto2', 'foto3'] as $foto)
                    <div class="form-group">
                        <label>Image {{ substr($foto, -1) }}</label>
                        <input type="file" name="{{ $foto }}" class="form-control">
                        @error($foto)
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @if(isset($obat) && $obat->$foto)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $obat->$foto) }}" width="100">
                            </div>
                        @endif
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                <a href="{{ route('obat.manage') }}" class="btn btn-light">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
