@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            <p class="card-description">
                Form Tambah Data Pengiriman
            </p>
            <form class="forms-sample" action="{{ route('pengiriman.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="id_penjualan">Penjualan</label>
                    <select class="form-control" id="id_penjualan" name="id_penjualan" required>
                        <option value="">Pilih Penjualan</option>
                        @foreach($penjualan as $pj)
                            <option value="{{ $pj->id }}">
                                Penjualan #{{ $pj->id }} - {{ $pj->created_at->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_penjualan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="no_invoice">No. Invoice</label>
                    <input type="text" class="form-control" id="no_invoice" name="no_invoice" 
                           value="{{ old('no_invoice') }}" required>
                    @error('no_invoice')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_kirim">Tanggal Kirim</label>
                            <input type="datetime-local" class="form-control" id="tgl_kirim" name="tgl_kirim" 
                                   value="{{ old('tgl_kirim') }}" required>
                            @error('tgl_kirim')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_tiba">Tanggal Tiba</label>
                            <input type="datetime-local" class="form-control" id="tgl_tiba" name="tgl_tiba" 
                                   value="{{ old('tgl_tiba') }}">
                            @error('tgl_tiba')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="status_kirim">Status Pengiriman</label>
                    <select class="form-control" id="status_kirim" name="status_kirim" required>
                        <option value="" disabled selected>Pilih Status</option>
                        <option value="Sedang Dikirim" {{ old('status_kirim') == 'Sedang Dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                        <option value="Tiba Di Tujuan" {{ old('status_kirim') == 'Tiba Di Tujuan' ? 'selected' : '' }}>Tiba Di Tujuan</option>
                    </select>
                    @error('status_kirim')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_kurir">Nama Kurir</label>
                            <input type="text" class="form-control" id="nama_kurir" name="nama_kurir" maxlength="30"
                                   value="{{ old('nama_kurir') }}" required>
                            @error('nama_kurir')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="telpon_kurir">Telepon Kurir</label>
                            <input type="text" class="form-control" id="telpon_kurir" name="telpon_kurir" maxlength="15"
                                   value="{{ old('telpon_kurir') }}" required>
                            @error('telpon_kurir')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="bukti_foto">Bukti Foto</label>
                    <input type="file" class="form-control" id="bukti_foto" name="bukti_foto" 
                           accept="image/*" capture="camera">
                    <small class="text-muted">Format: JPG, PNG, JPEG</small>
                    @error('bukti_foto')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                <a href="{{ route('pengiriman.manage') }}" class="btn btn-light">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection