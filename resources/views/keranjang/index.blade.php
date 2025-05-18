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
                    @if($cartItem->count() > 0)
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
                                @foreach($cartItem as $item)
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
                                        <input type="number" class="form-control quantity-input" 
                                            value="{{ $item->jumlah_beli }}" min="1" max="{{ $item->obat->stok + $item->jumlah_beli }}"
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
                    <div class="summary-item">
                        <span class="text">Total Terpilih</span>
                        <span class="price" id="selected-total">Rp0</span>
                    </div>
                    <button type="submit" form="checkout-form" class="btn btn-primary btn-block" id="checkout-btn" disabled>
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
        $('.remove-item').click(function(e) {
            e.preventDefault(); // Tambahkan ini untuk mencegah default action
            
            if(confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                const id = $(this).data('id');
                const $row = $(this).closest('tr'); // Dapatkan baris yang akan dihapus
                
                $.ajax({
                    url: '/keranjang/remove/' + id,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.success) {
                            // Hapus baris dari DOM
                            $row.fadeOut(300, function() {
                                $(this).remove();
                                calculateSelectedTotal(); // Update total
                                
                                // Jika keranjang kosong, reload halaman
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

        // Individual checkbox change
        $(document).on('change', '.item-checkbox', function() {
            if ($('.item-checkbox:checked').length === $('.item-checkbox').length) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
            calculateSelectedTotal();
        });

        // Calculate selected total
        function calculateSelectedTotal() {
            let total = 0;
            let checkedItems = 0;
            
            $('.item-checkbox:checked').each(function() {
                const itemId = $(this).val();
                const subtotalText = $(this).closest('tr').find('td:nth-child(5)').text();
                const subtotal = parseInt(subtotalText.replace(/[^\d]/g, ''));
                total += subtotal;
                checkedItems++;
            });

            $('#selected-total').text('Rp' + total.toLocaleString('id-ID'));
            
            // Enable/disable checkout button
            if (checkedItems > 0) {
                $('#checkout-btn').prop('disabled', false);
            } else {
                $('#checkout-btn').prop('disabled', true);
            }
        }

        // Initial calculation
        calculateSelectedTotal();
    });
</script>
@endpush

@section('footer')
    @include('fe.footer')
@endsection