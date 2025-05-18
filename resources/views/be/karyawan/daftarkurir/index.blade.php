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
                            <h4 class="card-title">{{ $title }}</h4>
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>No HP</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kurirs as $nmr => $kurir)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>{{ $kurir->name }}</td>
                                            <td>{{ $kurir->email }}</td>
                                            <td>{{ $kurir->no_hp }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('karyawan.daftarkurir.show', $kurir->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    <form action="{{ route('karyawan.daftarkurir.destroy', $kurir->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kurir ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm rounded-pill" type="submit">
                                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">Tidak ada kurir.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
