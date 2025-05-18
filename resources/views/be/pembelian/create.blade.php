@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-12 grid-margin stretch-card">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Tambah Pembelian</h4>
            <p class="card-description">Form Pembelian Obat</p>

            <form class="forms-sample" action="{{ route('pembelian.store') }}" method="POST" id="form-pembelian">
                @csrf
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="no_nota">No Nota</label>
                            <input type="text" class="form-control" id="no_nota" name="no_nota" value="{{ $no_nota }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tgl_pembelian">Tanggal Pembelian</label>
                            <input type="date" class="form-control" id="tgl_pembelian" name="tgl_pembelian" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_distributor">Distributor</label>
                            <select class="form-control" id="id_distributor" name="id_distributor" required>
                                <option value="" disabled selected>Pilih Distributor</option>
                                @foreach($distributors as $distributor)
                                <option value="{{ $distributor->id }}">{{ $distributor->nama_distributor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Detail Pembelian</label>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="detail-table">
                            <thead>
                                <tr>
                                    <th>Obat</th>
                                    <th width="150">Jumlah Beli</th>
                                    <th width="200">Harga Beli</th>
                                    <th width="200">Subtotal</th>
                                    <th width="50">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="id_obat[]" class="form-control obat-select" required>
                                            <option value="">Pilih Obat</option>
                                            @foreach($obats as $obat)
                                            <option value="{{ $obat->id }}" data-harga="{{ $obat->harga_beli }}">{{ $obat->nama_obat }}</option>
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
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="add-row">
                        <i class="bi bi-plus"></i> Tambah Obat
                    </button>
                </div>

                <div class="row mt-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_bayar">Total Bayar</label>
                            <input type="number" class="form-control" id="total_bayar" name="total_bayar" readonly>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                <a href="{{ route('pembelian.manage') }}" class="btn btn-light">Batal</a>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Fungsi untuk menambahkan row baru
    $('#add-row').click(function() {
        var newRow = `
        <tr>
            <td>
                <select name="obat_id[]" class="form-control obat-select" required>
                    <option value="">Pilih Obat</option>
                    @foreach($obats as $obat)
                    <option value="{{ $obat->id }}" data-harga="{{ $obat->harga_beli }}">{{ $obat->nama_obat }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="jumlah[]" class="form-control jumlah" min="1" value="1" required>
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

        // Aktifkan semua tombol hapus kecuali yang pertama
        $('#detail-table tbody tr').each(function(index) {
            if (index === 0) {
                $(this).find('.remove-row').prop('disabled', true);
            } else {
                $(this).find('.remove-row').prop('disabled', false);
            }
        });
    });

    // Fungsi untuk menghapus row
    $(document).on('click', '.remove-row', function() {
        if ($('#detail-table tbody tr').length > 1) {
            $(this).closest('tr').remove();
            calculateTotal();

            // Jika hanya tersisa 1 row, nonaktifkan tombol hapus
            if ($('#detail-table tbody tr').length === 1) {
                $('#detail-table tbody tr .remove-row').prop('disabled', true);
            }
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
        $('#total_bayar').val(total.toFixed(2));
    }

    // Event listener untuk perubahan jumlah dan harga
    $(document).on('input', '.jumlah, .harga', function() {
        var row = $(this).closest('tr');
        calculateSubtotal(row);
    });

    // Event listener untuk perubahan pilihan obat
    $(document).on('change', '.obat-select', function() {
        var selectedOption = $(this).find('option:selected');
        var harga = selectedOption.data('harga');
        var row = $(this).closest('tr');
        row.find('.harga').val(harga || '');
        calculateSubtotal(row);
    });

    // Hitung subtotal awal untuk row pertama
    calculateSubtotal($('#detail-table tbody tr:first'));
});
</script>
@endsection