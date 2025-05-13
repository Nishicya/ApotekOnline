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
                            <form method="POST" action="{{ route('jenis-pengiriman.update', $jenisPengiriman->id) }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="jenis_kirim">Jenis Kirim</label>
                                    <select class="form-control" id="jenis_kirim" name="jenis_kirim" required>
                                        <option value="">Pilih Jenis Kirim</option>
                                        @foreach($jenisKirimOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $jenisPengiriman->jenis_kirim == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nama_ekspedisi">Nama Ekspedisi</label>
                                    <input type="text" class="form-control" id="nama_ekspedisi" name="nama_ekspedisi" value="{{ old('nama_ekspedisi', $jenisPengiriman->nama_ekspedisi) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="logo_ekspedisi">Logo Ekspedisi</label>
                                    <input type="file" class="form-control" id="logo_ekspedisi" name="logo_ekspedisi">
                                    <small class="text-muted">Format: JPEG, PNG, JPG, GIF</small>
                                    
                                    @if($jenisPengiriman->logo_ekspedisi)
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . $jenisPengiriman->logo_ekspedisi) }}" width="100" class="img-thumbnail">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" class="form-check-input" id="delete_logo" name="delete_logo" value="1">
                                                <label class="form-check-label text-danger" for="delete_logo">
                                                    <i class="fas fa-trash-alt"></i> Hapus Logo
                                                </label>
                                            </div>
                                        </div>
                                    @endif
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteLogo = document.getElementById('delete_logo');
        if (deleteLogo) {
            deleteLogo.addEventListener('change', function() {
                if (this.checked && !confirm('Anda yakin ingin menghapus logo ini?')) {
                    this.checked = false;
                }
            });
        }
    });
</script>
@endsection