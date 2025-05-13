@extends('be.master')

@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>

            <form action="{{ route('penjualan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="tgl_penjualan">Tanggal Penjualan</label>
                    <input type="date" name="tgl_penjualan" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="id_pelanggan">Pelanggan</label>
                    <select name="id_pelanggan" class="form-control @error('id_pelanggan') is-invalid @enderror" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach ($pelanggan as $p)
                            <option value="{{ $p->id }}" {{ old('id_pelanggan') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pelanggan }} - {{ $p->email}}
                            </option>
                        @endforeach
                    </select>
                    @error('id_pelanggan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id_metode_bayar">Metode Pembayaran</label>
                    <select name="id_metode_bayar" class="form-control @error('id_metode_bayar') is-invalid @enderror" required>
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        @foreach ($metodeBayar as $mb)
                            <option value="{{ $mb->id }}" {{ old('id_metode_bayar') == $mb->id ? 'selected' : '' }}>
                                @if($mb->url_logo)
                                    <img src="{{ asset('storage/' . $mb->url_logo) }}" alt="{{ $mb->metode_pembayaran }}" style="height: 20px; margin-right: 10px;">
                                @endif
                                {{ $mb->metode_pembayaran }} 
                                ({{ $mb->tempat_bayar }})
                                @if($mb->no_rekening)
                                    - {{ $mb->no_rekening }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('id_metode_bayar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="id_jenis_kirim">Jenis Pengiriman</label>
                    <select name="id_jenis_kirim" class="form-control" required>
                        <option value="">-- Pilih Jenis Pengiriman --</option>
                        @foreach ($jenisPengiriman as $jk)
                            <option value="{{ $jk->id }}">{{ $jk->nama_pengiriman }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="ongkos_kirim">Ongkos Kirim</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number" name="ongkos_kirim" class="form-control" step="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="biaya_app">Biaya Aplikasi</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number" name="biaya_app" class="form-control" step="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="total_bayar">Total Bayar</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number" name="total_bayar" class="form-control" step="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status_order">Status Order</label>
                    <select name="status_order" id="status_order" class="form-control @error('status_order') is-invalid @enderror" required>
                        <option value="">-- Pilih Status --</option>
                        @foreach (\App\Models\Penjualan::getStatusOrderOptions() as $status)
                            <option value="{{ $status }}" {{ old('status_order') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('status_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="keterangan_status">Keterangan Status</label>
                    <textarea name="keterangan_status" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label for="url_resep">Upload Resep (hanya jika ada obat keras)</label>
                    <input type="file" name="url_resep" accept="image/*" class="form-control" onchange="previewImage(event)">
                    <div class="mt-2">
                        <img id="resep-preview" src="#" alt="Preview Resep" style="max-width: 200px; display: none; border: 1px solid #ccc; padding: 5px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('resep-preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }
</script>
@endsection
