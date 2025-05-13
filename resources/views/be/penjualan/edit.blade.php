@extends('be.master')

@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>

            <form action="{{ route('penjualan.update', $penjualan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Metode Bayar</label>
                    <select name="id_metode_bayar" class="form-control">
                        @foreach ($metodeBayars as $metode)
                            <option value="{{ $metode->id }}" {{ $penjualan->id_metode_bayar == $metode->id ? 'selected' : '' }}>
                                {{ $metode->nama_metode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="datetime-local" name="tgl_penjualan" value="{{ old('tgl_penjualan', $penjualan->tgl_penjualan) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Ongkos Kirim</label>
                    <input type="number" step="0.01" name="ongkos_kirim" value="{{ old('ongkos_kirim', $penjualan->ongkos_kirim) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Biaya App</label>
                    <input type="number" step="0.01" name="biaya_app" value="{{ old('biaya_app', $penjualan->biaya_app) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Total Bayar</label>
                    <input type="number" step="0.01" name="total_bayar" value="{{ old('total_bayar', $penjualan->total_bayar) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Status Order</label>
                    <input type="text" name="status_order" value="{{ old('status_order', $penjualan->status_order) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Keterangan Status</label>
                    <textarea name="keterangan_status" class="form-control">{{ old('keterangan_status', $penjualan->keterangan_status) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Jenis Pengiriman</label>
                    <select name="id_jenis_kirim" class="form-control">
                        @foreach ($jenisPengiriman as $jenis)
                            <option value="{{ $jenis->id }}" {{ $penjualan->id_jenis_kirim == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Pelanggan</label>
                    <select name="id_pelanggan" class="form-control">
                        @foreach ($pelanggans as $pel)
                            <option value="{{ $pel->id }}" {{ $penjualan->id_pelanggan == $pel->id ? 'selected' : '' }}>
                                {{ $pel->nama }} - {{ $pel->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jika ada resep sebelumnya --}}
                @if ($penjualan->url_resep)
                    <div class="form-group">
                        <label>Resep Lama</label><br>
                        <img src="{{ asset('storage/' . $penjualan->url_resep) }}" alt="Resep" class="img-thumbnail" style="max-width: 250px;">
                    </div>
                @endif

                <div class="form-group">
                    <label>Upload Resep Baru (jika ada)</label>
                    <input type="file" name="url_resep" class="form-control-file">
                    <small class="form-text text-muted">Hanya perlu diisi jika ingin mengganti resep lama.</small>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection