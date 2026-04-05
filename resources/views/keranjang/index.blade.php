@extends('fe.master')

@section('page_title', 'HEALTHIFY - Keranjang')

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
                                <tr data-item-id="{{ $item->id }}">
                                    <td>
                                        <input type="checkbox" name="selected_items[]" 
                                            value="{{ $item->id }}" class="item-checkbox">
                                    </td>
                                    <td>
                                        <img src="{{ asset('storage/' . $item->obat->foto1) }}" alt="{{ $item->obat->nama_obat }}" width="50">
                                        {{ $item->obat->nama_obat }}
                                    </td>
                                    <td class="item-price" data-price="{{ $item->obat->harga_jual }}">
                                        Rp{{ number_format($item->obat->harga_jual, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="quantity-container" style="display: flex; align-items: center; gap: 5px;">
                                            <button type="button" class="btn btn-sm btn-default quantity-decrease" 
                                                data-id="{{ $item->id }}" 
                                                style="padding: 5px 10px; background: #f5f5f5; border: 1px solid #ccc; cursor: pointer; border-radius: 3px;"
                                                title="Kurangi">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                            <input type="text" class="quantity-display" 
                                                value="{{ $item->jumlah_order }}" readonly
                                                style="width: 50px; text-align: center; border: 1px solid #ddd; padding: 5px; border-radius: 3px; background: #fff;">
                                            <button type="button" class="btn btn-sm btn-default quantity-increase" 
                                                data-id="{{ $item->id }}" 
                                                data-max="{{ $item->obat->stok + $item->jumlah_order }}"
                                                style="padding: 5px 10px; background: #f5f5f5; border: 1px solid #ccc; cursor: pointer; border-radius: 3px;"
                                                title="Tambah">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="item-subtotal" data-subtotal="{{ $item->subtotal }}">
                                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
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
                    <button type="submit" form="checkout-form" class="btn primary-btn btn-block" id="checkout-btn">
                        Lanjut ke Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
// Log langsung - test apakah script loading
console.log('=== KERANJANG SCRIPT LOADING ===');

// Setup dengan vanilla JS - lebih reliable
document.addEventListener('DOMContentLoaded', function() {
    console.log('✓ DOM Ready');
    
    // Ambil semua tombol
    var increaseButtons = document.querySelectorAll('.quantity-increase');
    var decreaseButtons = document.querySelectorAll('.quantity-decrease');
    
    console.log('✓ Increase buttons found:', increaseButtons.length);
    console.log('✓ Decrease buttons found:', decreaseButtons.length);
    
    // Setup increase buttons
    increaseButtons.forEach(function(btn, index) {
        console.log('  Setting up increase button', index);
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('>>> INCREASE CLICKED <<<');
            handleIncrease(this);
        });
    });
    
    // Setup decrease buttons
    decreaseButtons.forEach(function(btn, index) {
        console.log('  Setting up decrease button', index);
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('>>> DECREASE CLICKED <<<');
            handleDecrease(this);
        });
    });
    
    console.log('✓ All buttons initialized');
    
    // Setup delete/remove buttons
    var removeButtons = document.querySelectorAll('.remove-item');
    console.log('✓ Remove buttons found:', removeButtons.length);
    
    removeButtons.forEach(function(btn, index) {
        console.log('  Setting up remove button', index);
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('>>> DELETE CLICKED <<<');
            handleDelete(this);
        });
    });
    
    function handleIncrease(button) {
        console.log('handleIncrease called');
        var row = button.closest('tr');
        var displayInput = row.querySelector('.quantity-display');
        var currentQty = parseInt(displayInput.value) || 0;
        var maxQty = parseInt(button.getAttribute('data-max')) || 999;
        var id = button.getAttribute('data-id');
        
        console.log('Current qty:', currentQty);
        console.log('Max qty:', maxQty);
        console.log('ID:', id);
        
        if (currentQty < maxQty) {
            var newQty = currentQty + 1;
            console.log('Increasing to:', newQty);
            updateQuantity(row, id, newQty);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: 'Tidak bisa menambah jumlah lagi',
                timer: 2000,
                showConfirmButton: false
            });
        }
    }
    
    function handleDecrease(button) {
        console.log('handleDecrease called');
        var row = button.closest('tr');
        var displayInput = row.querySelector('.quantity-display');
        var currentQty = parseInt(displayInput.value) || 0;
        var id = button.getAttribute('data-id');
        
        console.log('Current qty:', currentQty);
        console.log('ID:', id);
        
        if (currentQty > 1) {
            var newQty = currentQty - 1;
            console.log('Decreasing to:', newQty);
            updateQuantity(row, id, newQty);
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Jumlah Minimal',
                text: 'Jumlah minimal adalah 1 item',
                timer: 2000,
                showConfirmButton: false
            });
        }
    }
    
    function handleDelete(button) {
        console.log('handleDelete called');
        
        Swal.fire({
            title: 'Hapus Produk?',
            text: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteItem(button);
            }
        });
    }
    
    function deleteItem(button) {
        console.log('deleteItem called');
        
        var id = button.getAttribute('data-id');
        var row = button.closest('tr');
        
        console.log('Deleting item ID:', id);
        
        // Get CSRF token
        var csrfToken = document.querySelector('[name="_token"]').value;
        
        // Send delete AJAX
        console.log('Sending DELETE to /keranjang/remove/' + id);
        $.ajax({
            url: '/keranjang/remove/' + id,
            method: 'DELETE',
            data: {
                _token: csrfToken
            },
            success: function(data) {
                console.log('✓ Delete response:', data);
                if (data.success) {
                    // Remove row dengan animasi fade
                    row.style.opacity = '0';
                    setTimeout(function() {
                        row.remove();
                        console.log('Row removed from DOM');
                        
                        // Refresh summary
                        updateSummary();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Dihapus',
                            text: 'Produk berhasil dihapus dari keranjang',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        
                        // Jika tidak ada item lagi, reload halaman
                        var remainingRows = document.querySelectorAll('tbody tr').length;
                        if (remainingRows === 0) {
                            console.log('No items left, reloading...');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    }, 300);
                }
            },
            error: function(xhr) {
                console.error('✗ Delete error:', xhr.status, xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menghapus produk dari keranjang',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }
    
    function updateSummary() {
        console.log('Updating summary');
        var totalHarga = 0;
        var jumlahItem = 0;
        
        // Hitung total dari item yang ter-checkbox
        var checkedItems = document.querySelectorAll('.item-checkbox:checked');
        console.log('Checked items:', checkedItems.length);
        
        var summaryHtml = '';
        
        checkedItems.forEach(function(checkbox) {
            var row = checkbox.closest('tr');
            var nama = row.querySelector('td:nth-child(2)').textContent.trim();
            var qty = row.querySelector('.quantity-display').value;
            var subtotalText = row.querySelector('.item-subtotal').textContent;
            var subtotal = parseFloat(row.querySelector('.item-subtotal').getAttribute('data-subtotal')) || 0;
            
            totalHarga += subtotal;
            jumlahItem++;
            
            summaryHtml += '<div class="mb-1"><strong>' + nama + '</strong><br>Qty: ' + qty + '<br>' + subtotalText + '</div>';
        });
        
        if (jumlahItem === 0) {
            summaryHtml = '<small>Pilih produk untuk melihat ringkasan.</small>';
        }
        
        document.getElementById('summary-list').innerHTML = summaryHtml;
        document.getElementById('selected-total').textContent = 'Rp' + totalHarga.toLocaleString('id-ID');
        document.getElementById('checkout-btn').disabled = (jumlahItem === 0);
    }
    
    // Setup checkbox event listeners
    var allCheckbox = document.getElementById('select-all');
    var itemCheckboxes = document.querySelectorAll('.item-checkbox');
    
    if (allCheckbox) {
        allCheckbox.addEventListener('change', function() {
            var checked = this.checked;
            itemCheckboxes.forEach(function(cb) {
                cb.checked = checked;
            });
            updateSummary();
        });
    }
    
    itemCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSummary();
            
            // Update select-all checkbox
            var allChecked = document.querySelectorAll('.item-checkbox:checked').length === itemCheckboxes.length;
            if (allCheckbox) {
                allCheckbox.checked = allChecked;
            }
        });
    });
    
    // Initial summary update
    updateSummary();

    
    function updateQuantity(row, id, newQuantity) {
        console.log('updateQuantity called - ID:', id, 'Qty:', newQuantity);
        
        var displayInput = row.querySelector('.quantity-display');
        var priceCell = row.querySelector('.item-price');
        var subtotalCell = row.querySelector('.item-subtotal');
        
        var price = parseFloat(priceCell.getAttribute('data-price')) || 0;
        var newSubtotal = newQuantity * price;
        
        console.log('Price:', price);
        console.log('New subtotal:', newSubtotal);
        
        // Update UI
        displayInput.value = newQuantity;
        subtotalCell.setAttribute('data-subtotal', newSubtotal);
        subtotalCell.textContent = 'Rp' + newSubtotal.toLocaleString('id-ID');
        
        console.log('UI Updated');
        
        // Get CSRF token
        var csrfToken = document.querySelector('[name="_token"]').value;
        
        // Send using jQuery AJAX (more reliable for Laravel)
        console.log('Sending AJAX to /keranjang/update/' + id);
        $.ajax({
            url: '/keranjang/update/' + id,
            method: 'POST',
            data: {
                _token: csrfToken,
                quantity: newQuantity
            },
            success: function(data) {
                console.log('✓ Backend response:', data);
                if (data.cart_count !== undefined) {
                    var cartCountEl = document.querySelector('.keranjang-count');
                    if (cartCountEl) {
                        cartCountEl.textContent = data.cart_count;
                    }
                }
            },
            error: function(xhr) {
                console.error('✗ Error:', xhr.status, xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Update',
                    text: 'Gagal memperbarui keranjang. Silakan coba lagi',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }
});
</script>

@section('footer')
    @include('fe.footer')
@endsection