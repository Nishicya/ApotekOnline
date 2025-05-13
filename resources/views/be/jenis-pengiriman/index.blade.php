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
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('jenis-pengiriman.create') }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Jenis Pengiriman
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Kirim</th>
                                            <th>Nama Ekspedisi</th>
                                            <th>Logo</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jenisPengiriman as $nmr => $data)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>
                                                @php
                                                    $jenisLabels = [
                                                        'ekonomi' => 'Ekonomi',
                                                        'kargo' => 'Kargo',
                                                        'regular' => 'Regular',
                                                        'same day' => 'Same Day',
                                                        'standar' => 'Standar'
                                                    ];
                                                    echo $jenisLabels[$data->jenis_kirim] ?? $data->jenis_kirim;
                                                @endphp
                                            </td>
                                            <td>{{ $data->nama_ekspedisi }}</td>
                                            <td>
                                                @if($data->logo_ekspedisi)
                                                    <img src="{{ asset('storage/' . $data->logo_ekspedisi) }}" width="50" class="rounded">
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('jenis-pengiriman.show', $data->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    <a href="{{ route('jenis-pengiriman.edit', $data->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('jenis-pengiriman.destroy', $data->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Hapus jenis pengiriman ini?')">
                                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
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