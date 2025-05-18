@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="az-content-body">
    <h2 class="az-content-title">{{ $title }}</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $kurir->name }}</p>
            <p><strong>Email:</strong> {{ $kurir->email }}</p>
            <p><strong>No HP:</strong> {{ $kurir->no_hp }}</p>
            <p><strong>Status:</strong> {{ $kurir->aktif ? 'Aktif' : 'Tidak Aktif' }}</p>
            <a href="{{ route('karyawan.daftarkurir.index') }}" class="btn btn-secondary">Kembali</a>
            <form action="{{ route('karyawan.daftarkurir.destroy', $kurir->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus kurir ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Hapus Kurir</button>
            </form>
        </div>
    </div>
</div>
@endsection
