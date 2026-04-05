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
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Invoice</th>
                                            <th>Penjualan</th>
                                            <th>Status</th>
                                            <th>Kurir</th>
                                            <th>Kontak</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @foreach($pengiriman as $nmr => $item)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>{{ $item->no_invoice }}</td>
                                            <td>Penjualan #{{ $item->penjualan->id }}</td>
                                            <td>
                                                <span class="badge rounded-pill 
                                                    @if($item->status_kirim == 'Menunggu Konfirmasi') bg-warning text-dark
                                                    @elseif($item->status_kirim == 'Sedang Dikirim') bg-info
                                                    @elseif($item->status_kirim == 'Tiba Di Tujuan') bg-success
                                                    @elseif($item->status_kirim == 'Dibatalkan') bg-danger
                                                    @else bg-secondary @endif">
                                                    {{ $item->status_kirim }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($item->kurir)
                                                    <strong>{{ $item->kurir->name }}</strong> ({{ $item->kurir->role }})
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->kurir)
                                                    {{ $item->kurir->no_hp ?? '-' }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status_kirim == 'Menunggu Konfirmasi')
                                                    <button type="button" class="btn btn-success btn-sm rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#confirmModal{{ $item->id }}" title="Konfirmasi & Assign Kurir">
                                                        <i class="fas fa-check-circle"></i> Konfirmasi
                                                    </button>
                                                @endif
                                                
                                                @if($item->status_kirim != 'Dibatalkan' && $item->status_kirim != 'Tiba Di Tujuan')
                                                    <button type="button" class="btn btn-danger btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $item->id }}" title="Batalkan Pengiriman">
                                                        <i class="fas fa-times-circle"></i> Batalkan
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Confirm Modal -->
                                        <div class="modal fade" id="confirmModal{{ $item->id }}" tabindex="-1" aria-labelledby="confirmModalLabel{{ $item->id }}" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <form id="confirmForm{{ $item->id }}" action="{{ route('daftarpengiriman.confirm', $item->id) }}" method="POST" onsubmit="return validateConfirm(this, {{ $item->id }})">
                                                @csrf
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="confirmModalLabel{{ $item->id }}">Konfirmasi Pengiriman & Pilih Kurir</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <div class="mb-3">
                                                    <label for="kurir{{ $item->id }}" class="form-label">Pilih Kurir</label>
                                                    <select class="form-select" id="kurir{{ $item->id }}" name="id_kurir" required>
                                                        <option value="">-- Pilih Kurir --</option>
                                                        @php
                                                            $kurirs = \App\Models\User::where('role', 'kurir')->where('name', 'like', '%' . \App\Models\JenisPengiriman::find($item->penjualan->id_jenis_kirim)?->jenis_kirim . '%')->get();
                                                            if ($kurirs->isEmpty()) {
                                                                $kurirs = \App\Models\User::where('role', 'kurir')->get();
                                                            }
                                                        @endphp
                                                        @foreach($kurirs as $kurir)
                                                            <option value="{{ $kurir->id }}">{{ $kurir->name }} - {{ $kurir->no_hp }}</option>
                                                        @endforeach
                                                    </select>
                                                  </div>
                                                  <p class="small text-muted">Jenis Pengiriman: <strong>{{ $item->penjualan->jenisPengiriman->jenis_kirim ?? '-' }}</strong></p>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                  <button type="submit" class="btn btn-success">Konfirmasi & Assign</button>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>

                                        <!-- Cancel Modal -->
                                        <div class="modal fade" id="cancelModal{{ $item->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $item->id }}" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <form id="cancelForm{{ $item->id }}" action="{{ route('daftarpengiriman.cancel', $item->id) }}" method="POST" onsubmit="return validateCancel(this, {{ $item->id }})">
                                                @csrf
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="cancelModalLabel{{ $item->id }}">Batalkan Pengiriman</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <div class="mb-3">
                                                    <label for="alasan{{ $item->id }}" class="form-label">Alasan Pembatalan</label>
                                                    <textarea class="form-control" id="alasan{{ $item->id }}" name="alasan" rows="3" required placeholder="Masukkan alasan pembatalan..."></textarea>
                                                  </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                  <button type="submit" class="btn btn-danger">Batalkan Pengiriman</button>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 500;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endsection

@section('scripts')
<script>
function validateConfirm(form, itemId) {
    const kurirSelect = document.getElementById('kurir' + itemId);
    const selectedValue = kurirSelect.value;
    
    console.log('Confirm form submitted for item:', itemId);
    console.log('Selected kurir ID:', selectedValue);
    
    if (!selectedValue || selectedValue === '') {
        alert('Harap pilih kurir terlebih dahulu');
        return false;
    }
    
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    console.log('Form data will be submitted');
    return true;
}

function validateCancel(form, itemId) {
    const alasanTextarea = document.getElementById('alasan' + itemId);
    const alasan = alasanTextarea.value.trim();
    
    console.log('Cancel form submitted for item:', itemId);
    console.log('Cancellation reason:', alasan);
    
    if (!alasan || alasan === '') {
        alert('Harap masukkan alasan pembatalan');
        return false;
    }
    
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    console.log('Form data will be submitted');
    return true;
}
</script>
@endsection