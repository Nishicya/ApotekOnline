@extends('fe.master')

@section('header')
    @include('fe.header')
@endsection

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="section-title">Keranjang Belanja</h2>
            </div>

            <div class="col-md-8">
                <form id="checkout-form" action="{{ route('checkout') }}" method="GET">
                    @csrf
                    @if($cartItems->count() > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_items[]" 
                                            value="{{ $item->id }}" class="item-checkbox">
                                    </td>
                                    <td>
                                        <img src="{{ asset('storage/' . $item->obat->foto1) }}" alt="{{ $item->obat->nama_obat }}" width="50">
                                        {{ $item->obat->nama_obat }}
                                    </td>
                                    <td>Rp{{ number_format($item->obat->harga_jual, 0, ',', '.') }}</td>
                                    <td>
                                        <input type="number" class="quantity-input" 
                                            value="{{ $item->jumlah_beli ?? $item->jumlah_order ?? 1 }}" min="1"
                                            max="{{ $item->obat->stok + ($item->jumlah_beli ?? $item->jumlah_order ?? 0) }}"
                                            data-id="{{ $item->id }}">
                                    </td>
                                    <td>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm remove-item" data-id="{{ $item->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            Keranjang belanja Anda kosong
                        </div>
                    @endif
                </form>
            </div>

            <div class="col-md-4">
                <div class="summary">
                    <h3>Ringkasan Belanja</h3>
                    <div id="summary-list">
                        <small>Pilih produk untuk melihat ringkasan.</small>
                    </div>
                    <div class="summary-item mt-2">
                        <span class="text">Total Terpilih</span>
                        <span class="price" id="selected-total">Rp0</span>
                    </div>
                    <button type="submit" form="checkout-form" class="btn btn-primary btn-block" id="checkout-btn">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Select All functionality
        $('#select-all').on('change', function() {
            var checked = $(this).is(':checked');
            $('.item-checkbox').prop('checked', checked);
            calculateSelectedTotal();
        });

        // Individual checkbox change (delegated for dynamic content)
        $(document).on('change', '.item-checkbox', function() {
            // Jika semua tercentang, select-all ikut centang, jika tidak, select-all tidak centang
            var allChecked = $('.item-checkbox').length > 0 && $('.item-checkbox:checked').length === $('.item-checkbox').length;
            $('#select-all').prop('checked', allChecked);
            calculateSelectedTotal();
        });

        // Saat halaman dimuat, pastikan select-all sinkron
        function syncSelectAll() {
            var allChecked = $('.item-checkbox').length > 0 && $('.item-checkbox:checked').length === $('.item-checkbox').length;
            $('#select-all').prop('checked', allChecked);
        }

        // Update quantity
        $('.quantity-input').change(function() {
            const id = $(this).data('id');
            const quantity = $(this).val();
            
            $.ajax({
                url: '/keranjang/update/' + id,
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: quantity
                },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    }
                }
            });
        });

        // Remove item
        // Pastikan tombol delete tidak bertindak sebagai submit form
        $(document).on('mousedown', '.remove-item', function(e) {
            // Hapus e.preventDefault(); agar tombol tetap bisa di-focus, tapi jangan submit form
            e.stopPropagation();
        });
        $(document).on('click', '.remove-item', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if(confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                const id = $(this).data('id');
                const $row = $(this).closest('tr');
                $.ajax({
                    url: '/keranjang/remove/' + id,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            $row.fadeOut(300, function() {
                                $(this).remove();
                                calculateSelectedTotal();
                                syncSelectAll();
                                if($('.item-checkbox').length === 0) {
                                    location.reload();
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            }
        });

        // Calculate selected total and update summary
        function calculateSelectedTotal() {
            let total = 0;
            let checkedItems = 0;
            let summaryHtml = '';
            $('.item-checkbox:checked').each(function() {
                const $row = $(this).closest('tr');
                // Ambil nama produk tanpa gambar
                const namaProduk = $row.find('td:nth-child(2)').clone().children().remove().end().text().trim();
                const jumlah = $row.find('.quantity-input').val();
                const subtotalText = $row.find('td:nth-child(5)').text().replace(/\s/g, '');
                // Pastikan parsing angka benar
                const subtotal = parseInt(subtotalText.replace(/[^\d]/g, '')) || 0;
                total += subtotal;
                checkedItems++;
                summaryHtml += `<div class="mb-1">
                    <strong>${namaProduk}</strong><br>
                    Jumlah: ${jumlah}<br>
                    Subtotal: ${subtotalText}
                </div>`;
            });

            if (checkedItems === 0) {
                summaryHtml = '<small>Pilih produk untuk melihat ringkasan.</small>';
            }
            $('#summary-list').html(summaryHtml);
            $('#selected-total').text('Rp' + total.toLocaleString('id-ID'));

            // Enable/disable checkout button
            $('#checkout-btn').prop('disabled', checkedItems === 0);
        }

        // Initial calculation & sync select-all
        calculateSelectedTotal();
        syncSelectAll();
    });
</script>
@endpush

@section('footer')
    @include('fe.footer')
@endsection