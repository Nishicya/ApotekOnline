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
                            @if(auth()->user()->role !== 'pemilik')
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus-circle me-2"></i> Add New Pelanggan
                                </a>
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Alamat</th>
                                            <th>Kota</th>
                                            <th>Provinsi</th>
                                            <th>Kodepos</th>
                                            <th>Alamat2</th>
                                            <th>Kota2</th>
                                            <th>Provinsi2</th>
                                            <th>Kodepos2</th>
                                            <th>Alamat3</th>
                                            <th>Kota3</th>
                                            <th>Provinsi3</th>
                                            <th>Kodepos3</th>
                                            <th>Foto</th>
                                            <th>KTP</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pelanggans as $nmr => $data)
                                            <tr>
                                                <td>{{ $nmr + 1 }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->nama_pelanggan, 20) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->email, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->no_hp, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->alamat1, 30) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->kota1, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->propinsi1, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->kodepos1, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->alamat2, 30) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->kota2, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->propinsi2, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->kodepos2, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->alamat3, 30) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->kota3, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->propinsi3, 15) }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($data->kodepos3, 15) }}</td>
                                                <td>
                                                @if($data->foto)
                                                    <img src="{{ asset('storage/' . $data->foto) }}" width="50">
                                                @endif
                                                </td>
                                                <td>
                                                @if($data->url_ktp)
                                                    <img src="{{ asset('storage/' . $data->url_ktp) }}" width="50">
                                                @endif
                                                </td>
                                                @if(auth()->user()->role !== 'pemilik')
                                                <td>
                                                   <div class="btn-group" role="group">
                                                        <a href="{{ route('pelanggan.edit', $data->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                        <form action="{{ route('pelanggan.destroy', $data->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Hapus pelanggan ini?')">
                                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data pelanggan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div> <!-- table-responsive -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteConfirm(id) {
        if (confirm('Yakin ingin menghapus pelanggan ini?')) {
            document.getElementById('deleteForm' + id).submit();
        }
    }
</script>
@endsection
