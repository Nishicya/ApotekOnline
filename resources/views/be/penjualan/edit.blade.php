@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="col-12 grid-margin stretch-card">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Penjualan #{{ $penjualan->no_nota }}</h4>
            <p class="card-description">Form Edit Penjualan Obat</p>

            <form class="forms-sample" action="{{ route('penjualan.update', $penjualan->id) }}" method="POST" id="form-penjualan" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="no_nota">No Nota</label>
                            <input type="text" class="form-control" id="no_nota" value="{{ $penjualan->no_nota }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tgl_penjualan">Tanggal Penjualan</label>
                            <input type="date" class="form-control" id="tgl_penjualan" name="tgl_penjualan" 
                                   value="{{ old('tgl_penjualan', $penjualan->tgl_penjualan) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_pelanggan">Pelanggan</label>
                            <select class="form-control" id="id_pelanggan" name="id_pelanggan" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach($pelanggan as $p)
                                <option value="{{ $p->id }}" 
                                    {{ old('id_pelanggan', $penjualan->id_pelanggan) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_pelanggan }} - {{ $p->email }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_metode_bayar">Metode Pembayaran</label>
                            <select class="form-control" id="id_metode_bayar" name="id_metode_bayar" required>
                                <option value="">Pilih Metode</option>
                                @foreach($metodeBayar as $mb)
                                <option value="{{ $mb->id }}" 
                                    {{ old('id_metode_bayar', $penjualan->id_metode_bayar) == $mb->id ? 'selected' : '' }}>
                                    @if($mb->url_logo)
                                        <img src="{{ asset('storage/'.$mb->url_logo) }}" alt="{{ $mb->metode_pembayaran }}" style="height: 20px; margin-right: 10px;">
                                    @endif
                                    {{ $mb->metode_pembayaran }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_jenis_kirim">Jenis Pengiriman</label>
                            <select class="form-control" id="id_jenis_kirim" name="id_jenis_kirim" required>
                                <option value="">Pilih Pengiriman</option>
                                @foreach($jenisPengiriman as $jk)
                                <option value="{{ $jk->id }}" 
                                    {{ old('id_jenis_kirim', $penjualan->id_jenis_kirim) == $jk->id ? 'selected' : '' }}>
                                    {{ $jk->nama_ekspedisi }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status_order">Status Order</label>
                            <select class="form-control" id="status_order" name="status_order" required>
                                <option value="">Pilih Status</option>
                                @foreach($statusOptions as $status)
                                <option value="{{ $status }}" 
                                    {{ old('status_order', $penjualan->status_order) == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Detail Penjualan</label>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="detail-table">
                            <thead>
                                <tr>
                                    <th>Obat</th>
                                    <th width="150">Jumlah</th>
                                    <th width="200">Harga Jual</th>
                                    <th width="200">Subtotal</th>
                                    <th width="50">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(old('id_obat', $penjualan->detailPenjualans) as $index => $detail)
                                <tr>
                                    <td>
                                        <select name="id_obat[]" class="form-control obat-select" required>
                                            <option value="">Pilih Obat</option>
                                            @foreach($obats as $obat)
                                            <option value="{{ $obat->id }}" 
                                                data-harga="{{ $obat->harga_jual }}"
                                                {{ (old('id_obat.'.$index, is_object($detail) ? $detail->id_obat : $detail) == $obat->id ? 'selected' : '' }}>
                                                {{ $obat->nama_obat }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah_beli[]" class="form-control jumlah" 
                                               min="1" value="{{ old('jumlah_beli.'.$index, is_object($detail) ? $detail->jumlah_beli : 1) }}" required>
                                    </td>
                                    <td>
                                        <input type="number" name="harga_beli[]" class="form-control harga" 
                                               value="{{ old('harga_beli.'.$index, is_object($detail) ? $detail->harga_beli : '') }}" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control subtotal" 
                                               value="{{ old('harga_beli.'.$index, is_object($detail) ? $detail->subtotal : '') }}" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row p-0" style="width: 30px; height: 30px;">
                                            <i class="bi bi-trash" style="font-size: 15px; margin-left: 5px;"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="add-row">
                        <i class="bi bi-plus"></i> Tambah Obat
                    </button>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="keterangan_status">Keterangan Status</label>
                            <textarea name="keterangan_status" class="form-control" rows="2">{{ old('keterangan_status', $penjualan->keterangan_status) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="url_resep">Upload Resep (jika diperlukan)</label>
                            <input type="file" name="url_resep" class="form-control" onchange="previewImage(event)">
                            @if($penjualan->url_resep)
                            <div class="mt-2">
                                <img id="resep-preview" src="{{ asset('storage/'.$penjualan->url_resep) }}" alt="Resep saat ini" style="max-width: 200px;">
                                <p class="text-muted mt-1">Resep saat ini</p>
                            </div>
                            @else
                            <div class="mt-2">
                                <img id="resep-preview" src="#" alt="Preview Resep" style="max-width: 200px; display: none;">
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ongkos_kirim">Ongkos Kirim</label>
                            <input type="number" name="ongkos_kirim" class="form-control" 
                                   value="{{ old('ongkos_kirim', $penjualan->ongkos_kirim) }}" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="biaya_app">Biaya Aplikasi</label>
                            <input type="number" name="biaya_app" class="form-control" 
                                   value="{{ old('biaya_app', $penjualan->biaya_app) }}" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="total_bayar">Total Bayar</label>
                            <input type="number" name="total_bayar" class="form-control" 
                                   value="{{ old('total_bayar', $penjualan->total_bayar) }}" readonly>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                <a href="{{ route('penjualan.show', $penjualan->id) }}" class="btn btn-light">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Fungsi untuk menambahkan row baru
    $('#add-row').click(function() {
        var newRow = `
        <tr>
            <td>
                <select name="id_obat[]" class="form-control obat-select" required>
                    <option value="">Pilih Obat</option>
                    @foreach($obats as $obat)
                    <option value="{{ $obat->id }}" data-harga="{{ $obat->harga_jual }}">{{ $obat->nama_obat }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="jumlah_beli[]" class="form-control jumlah" min="1" value="1" required>
            </td>
            <td>
                <input type="number" name="harga_beli[]" class="form-control harga" required>
            </td>
            <td>
                <input type="number" class="form-control subtotal" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row p-0" style="width: 30px; height: 30px;">
                    <i class="bi bi-trash" style="font-size: 15px; margin-left: 5px;"></i>
                </button>
            </td>
        </tr>`;

        $('#detail-table tbody').append(newRow);
    });

    // Fungsi untuk menghapus row
    $(document).on('click', '.remove-row', function() {
        if ($('#detail-table tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotal();
        }
    });

    // Fungsi untuk menghitung subtotal dan total
    function calculateSubtotal(row) {
        var jumlah = parseFloat(row.find('.jumlah').val()) || 0;
        var harga = parseFloat(row.find('.harga').val()) || 0;
        var subtotal = jumlah * harga;
        row.find('.subtotal').val(subtotal.toFixed(2));
        calculateTotal();
    }

    function calculateTotal() {
        var total = 0;
        $('#detail-table tbody tr').each(function() {
            var subtotal = parseFloat($(this).find('.subtotal').val()) || 0;
            total += subtotal;
        });
        
        var ongkir = parseFloat($('[name="ongkos_kirim"]').val()) || 0;
        var biayaApp = parseFloat($('[name="biaya_app"]').val()) || 0;
        
        $('[name="total_bayar"]').val((total + ongkir + biayaApp).toFixed(2));
    }

    // Event listeners
    $(document).on('input', '.jumlah, .harga', function() {
        var row = $(this).closest('tr');
        calculateSubtotal(row);
    });

    $(document).on('change', '.obat-select', function() {
        var selectedOption = $(this).find('option:selected');
        var harga = selectedOption.data('harga');
        var row = $(this).closest('tr');
        row.find('.harga').val(harga || '');
        calculateSubtotal(row);
    });

    $(document).on('input', '[name="ongkos_kirim"], [name="biaya_app"]', function() {
        calculateTotal();
    });

    // Hitung subtotal awal untuk semua row yang ada
    $('#detail-table tbody tr').each(function() {
        calculateSubtotal($(this));
    });
});

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