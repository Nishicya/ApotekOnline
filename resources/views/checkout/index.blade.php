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
            <div class="col-md-7">
                <!-- Shipping Details -->
                <div class="billing-details">
                    <div class="section-title">
                        <h3 class="title">Informasi Pengiriman</h3>
                    </div>
                    {{-- Tampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin-bottom:0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(empty($selectedItems) || count($selectedItems) == 0)
                        <div class="alert alert-danger">
                            Tidak ada item yang dipilih untuk checkout. Silahkan kembali ke keranjang.
                        </div>
                        <a href="{{ route('keranjang') }}" class="primary-btn">Kembali ke Keranjang</a>
                    @else
                        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                            @csrf
                            
                            <!-- Hidden input for selected items -->
                            @foreach($selectedItems as $itemId)
                                <input type="hidden" name="selected_items[]" value="{{ $itemId }}">
                            @endforeach
                            
                            <div class="form-group">
                                <label>Alamat Pengiriman</label>
                                <select class="input" name="alamat_pengiriman" required>
                                    <option value="" disabled selected>
                                        Pilih Alamat
                                    </option>
                                    @if(!empty($pelanggan->alamat1) && $pelanggan->alamat1 != '-')
                                        <option value="{{ $pelanggan->alamat1 }}"
                                            {{ old('alamat_pengiriman', $pelanggan->alamat1) == $pelanggan->alamat1 ? 'selected' : '' }}>
                                            {{ $pelanggan->alamat1 }}
                                        </option>
                                    @endif
                                    @if(!empty($pelanggan->alamat2) && $pelanggan->alamat2 != '-')
                                        <option value="{{ $pelanggan->alamat2 }}"
                                            {{ old('alamat_pengiriman') == $pelanggan->alamat2 ? 'selected' : '' }}>
                                            {{ $pelanggan->alamat2 }}
                                        </option>
                                    @endif
                                    @if(!empty($pelanggan->alamat3) && $pelanggan->alamat3 != '-')
                                        <option value="{{ $pelanggan->alamat3 }}"
                                            {{ old('alamat_pengiriman') == $pelanggan->alamat3 ? 'selected' : '' }}>
                                            {{ $pelanggan->alamat3 }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Metode Pembayaran</label>
                                @foreach($metodeBayar as $metode)
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="id_metode_bayar" value="{{ $metode->id }}" required
                                            {{ old('id_metode_bayar') == $metode->id ? 'checked' : '' }}>
                                            {{ $metode->metode_pembayaran }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="form-group">
                                <label>Jenis Pengiriman</label>
                                <select class="input" name="id_jenis_kirim" required>
                                    <option value="" disabled selected>Pilih Jenis Pengiriman</option>
                                    @foreach($jenisPengiriman as $jenis)
                                    <option value="{{ $jenis->id }}" data-harga="{{ $jenis->harga }}" {{ old('id_jenis_kirim') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->jenis_kirim }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Catatan (Opsional)</label>
                                <textarea class="input" name="catatan">{{ old('catatan') }}</textarea>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            @if(!empty($selectedItems) && count($selectedItems) > 0)
                <!-- Order Details -->
                <div class="col-md-5 order-details">
                    <div class="section-title text-center">
                        <h3 class="title">Pesanan Anda</h3>
                    </div>
                    <div class="order-summary">
                        <div class="order-col">
                            <div><strong>PRODUK</strong></div>
                            <div><strong>TOTAL</strong></div>
                        </div>
                        <div class="order-products">
                            @foreach($keranjangItems as $item)
                            <div class="order-col">
                                <div>{{ $item->jumlah_beli }}x {{ $item->obat->nama_obat }}</div>
                                <div>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            </div>
                            @endforeach
                        </div>
                        <div class="order-col">
                            <div>Ongkos Kirim</div>
                            <div id="ongkir-text">Rp0</div>
                        </div>
                        <div class="order-col">
                            <div>Biaya Aplikasi</div>
                            <div id="biaya-app-text">Rp2.000</div>
                        </div>
                        <div class="order-col">
                            <div><strong>TOTAL</strong></div>
                            <div><strong class="order-total" id="total-text">
                                Rp{{ number_format($keranjangItems->sum('subtotal') + 2000, 0, ',', '.') }}
                            </strong></div>
                        </div>
                    </div>
                    
                    <div class="payment-method">
                        <div class="input-checkbox">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">
                                <span></span>
                                Saya telah membaca dan menyetujui <a href="#">syarat & ketentuan</a>
                            </label>
                        </div>
                    </div>
                    
                    <button type="button" class="primary-btn order-submit" id="pay-button">Proses Pesanan</button>
                </div>
            @endif
        </div>
    </div>
</div>
@if(!empty($selectedItems) && count($selectedItems) > 0)
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
    document.getElementById('pay-button').onclick = async function(e) {
        e.preventDefault();
        
        // Validate terms checkbox
        if (!document.getElementById('terms').checked) {
            alert('Anda harus menyetujui syarat & ketentuan');
            return;
        }

        const selectedItems = document.querySelectorAll('input[name="selected_items[]"]');
        if (selectedItems.length === 0) {
            alert('Tidak ada item yang dipilih untuk checkout');
            return;
        }

        // Get all form data including selected payment method
        const form = document.getElementById('checkout-form');
        const formData = new FormData(form);

        try {
            const form = document.getElementById('checkout-form');
            const formData = new FormData(form);
            const response = await fetch("{{ route('checkout.process') }}", {
                method: 'POST',
                headers:
                {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan saat memproses pesanan');
            }

            if (data.snapToken) {
                snap.pay(data.snapToken, {
                    onSuccess: function(result) {
                        window.location.href = `{{ route('payment.finish') }}?order_id=${result.order_id}&status_code=${result.status_code}&transaction_status=${result.transaction_status}`;
                    },
                    onPending: function(result) {
                        window.location.href = `{{ route('payment.finish') }}?order_id=${result.order_id}&status_code=${result.status_code}&transaction_status=${result.transaction_status}`;
                    },
                    onError: function(result) {
                        window.location.href = `{{ route('payment.finish') }}?order_id=${result.order_id}&status_code=${result.status_code}&transaction_status=${result.transaction_status}`;
                    }
                });
            } else if (data.redirect) {
                window.location.href = data.redirect;
            }
        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Terjadi kesalahan saat memproses pesanan');
        }
    };

    $(document).ready(function() {
            const subtotal = {{ $keranjangItems->sum('subtotal') }};
            
            // Initialize shipping cost if already selected
            const initialShipping = $('select[name="id_jenis_kirim"]').find('option:selected').data('harga') || 0;
            updateTotals(initialShipping);
            
            $('select[name="id_jenis_kirim"]').change(function() {
                const selectedOption = $(this).find('option:selected');
                const ongkir = selectedOption.data('harga') || 0;
                updateTotals(ongkir);
            });
            
            function updateTotals(ongkir) {
                const biayaApp = 2000;
                const total = subtotal + ongkir + biayaApp;

                $('#ongkir-text').text('Rp' + ongkir.toLocaleString('id-ID'));
                $('#biaya-app-text').text('Rp' + biayaApp.toLocaleString('id-ID'));
                $('#total-text').text('Rp' + total.toLocaleString('id-ID'));
            }

        });
    </script>
@endif
@endsection

@section('footer')
    @include('fe.footer')
@endsection