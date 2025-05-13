@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $title }}</h4>
            <p class="card-description">Formulir Pembelian Baru</p>

            <form id="pembelianForm" method="POST" action="{{ route('pembelian.store') }}">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tgl_pembelian">Tanggal Pembelian <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tgl_pembelian') is-invalid @enderror" 
                                   id="tgl_pembelian" name="tgl_pembelian" required
                                   value="{{ old('tgl_pembelian', date('Y-m-d')) }}">
                            @error('tgl_pembelian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_distributor">Distributor <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('id_distributor') is-invalid @enderror" 
                                    id="id_distributor" name="id_distributor" required>
                                <option value="">Pilih Distributor</option>
                                @foreach($distributors as $distributor)
                                    <option value="{{ $distributor->id }}" 
                                        {{ old('id_distributor') == $distributor->id ? 'selected' : '' }}
                                        data-nama="{{ $distributor->nama_distributor }}">
                                        {{ $distributor->nama_distributor }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_distributor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <hr>
                <h5 class="mb-3">Detail Pembelian Obat <span class="text-danger">*</span></h5>
                
                <div id="obatContainer">
                    @if(old('obat_id'))
                        @foreach(old('obat_id') as $index => $obatId)
                        <div class="row obat-row mb-3 g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="col-form-label">Obat</label>
                                <select class="form-control select2 obat-select" name="obat_id[]" required>
                                    <option value="">Pilih Obat</option>
                                    @foreach($obats as $obat)
                                        <option value="{{ $obat->id }}" 
                                            data-harga="{{ $obat->harga_beli }}"
                                            data-stok="{{ $obat->stok }}"
                                            {{ $obatId == $obat->id ? 'selected' : '' }}>
                                            {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('obat_id.'.$index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Jumlah</label>
                                <input type="number" class="form-control jumlah" 
                                       name="jumlah[]" placeholder="Qty" min="1" 
                                       value="{{ old('jumlah.'.$index, 1) }}" required>
                                @error('jumlah.'.$index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Harga Beli</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control harga" 
                                           name="harga_beli[]" placeholder="Harga" min="0" step="100" 
                                           value="{{ old('harga_beli.'.$index) }}" required>
                                </div>
                                @error('harga_beli.'.$index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Subtotal</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control subtotal" readonly>
                                    <input type="hidden" class="subtotal-hidden" name="subtotal[]">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Initial row -->
                        <div class="row obat-row mb-3 g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="col-form-label">Obat</label>
                                <select class="form-control select2 obat-select" name="obat_id[]" required>
                                    <option value="">Pilih Obat</option>
                                    @foreach($obats as $obat)
                                        <option value="{{ $obat->id }}" 
                                            data-harga="{{ $obat->harga_beli }}"
                                            data-stok="{{ $obat->stok }}">
                                            {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Jumlah</label>
                                <input type="number" class="form-control jumlah" 
                                       name="jumlah[]" placeholder="Qty" min="1" value="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Harga Beli</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control harga" 
                                           name="harga_beli[]" placeholder="Harga" min="0" step="100" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="col-form-label">Subtotal</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control subtotal" readonly>
                                    <input type="hidden" class="subtotal-hidden" name="subtotal[]">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <button type="button" id="addObat" class="btn btn-secondary btn-sm">
                            <i class="fas fa-plus-circle"></i> Tambah Obat
                        </button>
                    </div>
                </div>
                
                <hr>
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th width="60%">Total Pembelian:</th>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control fw-bold" id="total_bayar" 
                                                   value="0" readonly>
                                            <input type="hidden" name="total_bayar" id="total_bayar_hidden" value="0">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-save"></i> Simpan Pembelian
                    </button>
                    <a href="{{ route('pembelian.manage') }}" class="btn btn-light">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 40px;
        padding-top: 5px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }
    .obat-row {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    .remove-row {
        margin-bottom: 15px;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        width: '100%',
        placeholder: "Pilih item"
    });

    // Format number to Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Parse Rupiah format back to number
    function parseRupiah(rupiah) {
        return parseInt(rupiah.replace(/[^0-9]/g, ''));
    }

    // Calculate subtotal for a row
    function calculateSubtotal(row) {
        const jumlah = parseFloat(row.find('.jumlah').val()) || 0;
        const harga = parseFloat(row.find('.harga').val()) || 0;
        const subtotal = jumlah * harga;
        
        row.find('.subtotal').val(formatRupiah(subtotal));
        row.find('.subtotal-hidden').val(subtotal);
        return subtotal;
    }

    // Calculate total
    function calculateTotal() {
        let total = 0;
        
        $('.obat-row').each(function() {
            const subtotal = calculateSubtotal($(this));
            total += subtotal;
        });
        
        $('#total_bayar').val(formatRupiah(total));
        $('#total_bayar_hidden').val(total);
    }

    // Auto-fill harga when obat selected
    $(document).on('change', '.obat-select', function() {
        const harga = $(this).find(':selected').data('harga');
        const stok = $(this).find(':selected').data('stok');
        const row = $(this).closest('.obat-row');
        
        row.find('.harga').val(harga);
        row.find('.jumlah').attr('max', stok).val(1);
        calculateSubtotal(row);
        calculateTotal();
    });

    // Recalculate when jumlah or harga changes
    $(document).on('input', '.jumlah, .harga', function() {
        const row = $(this).closest('.obat-row');
        const jumlah = parseInt(row.find('.jumlah').val()) || 0;
        const stok = parseInt(row.find('.obat-select').find(':selected').data('stok')) || 0;
        
        if (jumlah > stok) {
            alert('Jumlah melebihi stok yang tersedia!');
            row.find('.jumlah').val(stok);
        }
        
        calculateSubtotal(row);
        calculateTotal();
    });

    $(document).ready(function() {
        // Fungsi tambah baris obat
        $('#addObat').click(function() {
            const newRow = `
                <div class="row obat-row mb-3">
                    <div class="col-md-4">
                        <select class="form-control select2 obat-select" name="obat_id[]" required>
                            <option value="">Pilih Obat</option>
                            @foreach($obats as $obat)
                                <option value="{{ $obat->id }}" 
                                    data-harga="{{ $obat->harga_beli }}"
                                    data-stok="{{ $obat->stok }}">
                                    {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control jumlah" name="jumlah[]" min="1" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control harga" name="harga_beli[]" readonly>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control subtotal" readonly>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            // Tambahkan baris baru
            $('#obatContainer').append(newRow);

            // Inisialisasi Select2 untuk dropdown obat
            $('#obatContainer .select2').last().select2({
                width: '100%',
                placeholder: "Pilih Obat"
            });
        });

        // Fungsi hapus baris (gunakan event delegation)
        $(document).on('click', '.remove-row', function() {
            if ($('.obat-row').length > 1) {
                $(this).closest('.obat-row').remove();
            } else {
                alert("Minimal harus ada 1 obat!");
            }
        });
    });

    // Remove row
    $(document).on('click', '.remove-row', function() {
        if ($('.obat-row').length > 1) {
            $(this).closest('.obat-row').remove();
            calculateTotal();
        } else {
            alert('Minimal harus ada satu obat dalam pembelian!');
        }
    });

    // Form validation
    $('#pembelianForm').on('submit', function(e) {
        let valid = true;
        
        // Reset error states
        $('.is-invalid').removeClass('is-invalid');
        
        // Validate at least one medicine
        if ($('.obat-row').length === 0) {
            alert('Harap tambahkan minimal satu obat');
            valid = false;
            e.preventDefault();
            return false;
        }
        
        // Validate each row
        $('.obat-row').each(function() {
            const $obatSelect = $(this).find('.obat-select');
            const $jumlah = $(this).find('.jumlah');
            const $harga = $(this).find('.harga');
            
            if (!$obatSelect.val()) {
                $obatSelect.addClass('is-invalid');
                valid = false;
            }
            
            if (!$jumlah.val() || $jumlah.val() < 1) {
                $jumlah.addClass('is-invalid');
                valid = false;
            }
            
            if (!$harga.val() || $harga.val() <= 0) {
                $harga.addClass('is-invalid');
                valid = false;
            }
            
            // Validate stok
            const stok = parseInt($obatSelect.find(':selected').data('stok')) || 0;
            if (parseInt($jumlah.val()) > stok) {
                $jumlah.addClass('is-invalid');
                alert('Jumlah melebihi stok yang tersedia untuk obat ' + $obatSelect.find(':selected').text());
                valid = false;
            }
        });
        
        if (!valid) {
            e.preventDefault();
            alert('Harap lengkapi semua data obat dengan benar');
            return false;
        }
        
        // Ensure total is calculated before submit
        calculateTotal();
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endpush