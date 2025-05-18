@extends('fe.master')

@section('header')
    @include('fe.header')
@endsection

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<div class="section" style="background: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title mb-4">
                    <h3 class="title" style="font-weight:700;"> My Orders List</h3>
                </div>
            </div>
            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Order Date</th>
                                        <th>No. Invoice</th>
                                        <th>Total</th>
                                        <th>Order Status</th>
                                        <th>Shipping Status</th>
                                        <th>Proof Photo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penjualans as $i => $penjualan)
                                    <tr style="background: #fff;">
                                        <td>{{ $i+1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($penjualan->tgl_penjualan)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge-pill badge badge-success" style="font-size:13px;">
                                                {{ $penjualan->pengiriman->no_invoice ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-danger font-weight-bold">
                                                Rp{{ number_format($penjualan->total_bayar,0,',','.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-pill 
                                                @if(strtolower($penjualan->status_order) == 'selesai') badge-success
                                                @elseif(strtolower($penjualan->status_order) == 'diproses') badge-info
                                                @elseif(strtolower($penjualan->status_order) == 'menunggu konfirmasi') badge-warning
                                                @else badge-secondary @endif"
                                                style="font-size:13px;
                                                @if(strtolower($penjualan->status_order) == 'selesai') background-color:#28a745 !important;color:#fff; @endif">
                                                {{ $penjualan->status_order }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($penjualan->pengiriman)
                                                <span class="badge badge-pill
                                                    @if($penjualan->pengiriman->status_kirim == 'Sedang Dikirim') badge-warning
                                                    @elseif($penjualan->pengiriman->status_kirim == 'Tiba Di Tujuan') badge-success
                                                    @else badge-secondary @endif"
                                                    style="font-size:13px;
                                                    @if($penjualan->pengiriman->status_kirim == 'Tiba Di Tujuan') background-color:#28a745 !important;color:#fff; @endif">
                                                    {{ $penjualan->pengiriman->status_kirim }}
                                                </span>
                                            @else
                                                <span class="badge badge-pill badge-secondary" style="font-size:13px;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($penjualan->pengiriman && $penjualan->pengiriman->bukti_foto)
                                                <a href="#" data-toggle="modal" data-target="#previewBuktiModal{{ $penjualan->pengiriman->id }}">
                                                    <img src="{{ Storage::url($penjualan->pengiriman->bukti_foto) }}" alt="Bukti" class="rounded shadow-sm" style="width:50px;height:50px;object-fit:cover;">
                                                </a>
                                                <!-- Modal Preview Bukti -->
                                                <div class="modal fade" id="previewBuktiModal{{ $penjualan->pengiriman->id }}" tabindex="-1" role="dialog" aria-labelledby="previewBuktiModalLabel{{ $penjualan->pengiriman->id }}" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="previewBuktiModalLabel{{ $penjualan->pengiriman->id }}">Preview Bukti Foto</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body text-center">
                                                        <img src="{{ Storage::url($penjualan->pengiriman->bukti_foto) }}" alt="Bukti" style="max-width:100%;max-height:400px;">
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column flex-md-row gap-2">
                                                <a href="{{ route('fe.pesanan.show', $penjualan->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                    <i class="fa fa-eye"></i> Detail
                                                </a>
                                                @if($penjualan->pengiriman && $penjualan->pengiriman->status_kirim == 'Sedang Dikirim' && $penjualan->status_order != 'Selesai')
                                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#konfirmasiModal{{ $penjualan->pengiriman->id }}">
                                                        <i class="fa fa-check"></i> Arrived
                                                    </button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="konfirmasiModal{{ $penjualan->pengiriman->id }}" tabindex="-1" role="dialog" aria-labelledby="konfirmasiModalLabel{{ $penjualan->pengiriman->id }}" aria-hidden="true">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <form action="{{ route('fe.pesanan.konfirmasi', $penjualan->pengiriman->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-header">
                                                              <h5 class="modal-title" id="konfirmasiModalLabel{{ $penjualan->pengiriman->id }}">Konfirmasi Pesanan</h5>
                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                              </button>
                                                            </div>
                                                            <div class="modal-body">
                                                              <div class="mb-3">
                                                                <label for="bukti_foto_{{ $penjualan->pengiriman->id }}" class="form-label">Upload Bukti Foto <span class="text-danger">*</span></label>
                                                                <input type="file" class="form-control" name="bukti_foto" id="bukti_foto_{{ $penjualan->pengiriman->id }}" required accept="image/*">
                                                              </div>
                                                              <div>
                                                                Pastikan Anda sudah menerima pesanan sebelum konfirmasi. Setelah konfirmasi, status pesanan akan menjadi <b>Selesai</b>.
                                                              </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                              <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                            </div>
                                                          </form>
                                                        </div>
                                                      </div>
                                                    </div>
                                                @elseif($penjualan->status_order == 'Selesai')
                                                    <span class="badge badge-success" style="background-color:#28a745 !important;color:#fff;">Done</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada pesanan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .section-title {
        margin-bottom: 24px;
        font-weight: 700;
        color: #222;
    }
    .badge, .badge-pill {
        font-size: 0.95em;
        font-weight: 500;
        letter-spacing: 0.02em;
    }
    .table td, .table th { vertical-align: middle; }
    .card { margin-bottom: 30px; border-radius: 12px; }
    .card-body { border-radius: 12px; }
    .btn-outline-info { border-radius: 20px; }
    .btn-success, .btn-outline-info { min-width: 90px; }
    .shadow-sm { box-shadow: 0 2px 8px rgba(0,0,0,0.07) !important; }
    .table-hover tbody tr:hover { background: #f1f7ff; }
</style>
@endpush

@section('footer')
    @include('fe.footer')
@endsection
