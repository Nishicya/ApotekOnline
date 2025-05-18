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
                            @if(auth()->user()->role == 'admin' || auth()->user()->role == 'kasir')
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('penjualan.create') }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Penjualan
                                </a>
                            </div>
                            @else
                            @endif
                            
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
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Metode Bayar</th>
                                            <th>Total Bayar</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penjualan as $nmr => $item)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_penjualan)->translatedFormat('d M Y') }}</td>
                                            <td>{{ $item->pelanggan->nama_pelanggan ?? '-' }}</td>
                                            <td>
                                                {{ $item->metodeBayar->metode_pembayaran ?? '-' }}
                                            </td>
                                            <td>Rp{{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge rounded-pill 
                                                    @if(strtolower($item->status_order) == 'selesai') bg-success
                                                    @elseif(strtolower($item->status_order) == 'diproses') bg-warning text-dark
                                                    @else bg-secondary @endif">
                                                    {{ ucfirst($item->status_order) }}
                                                </span>
                                                @if($item->pengiriman && strtolower($item->pengiriman->status_kirim) == 'tiba di tujuan')
                                                    <span class="badge rounded-pill bg-dark ms-1">Tiba Di Tujuan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(auth()->user()->role == 'pemilik')
                                                        <a href="{{ route('laporanpenjualan.show', $item->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                    @elseif(auth()->user()->role == 'kasir')
                                                        <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                        @if($item->status_order == 'Menunggu Konfirmasi')
                                                            <button type="button" class="btn btn-success btn-sm rounded-pill ms-2" data-bs-toggle="modal" data-bs-target="#konfirmasiModal{{ $item->id }}">
                                                                <i class="fas fa-check-circle me-1"></i> Konfirmasi
                                                            </button>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="konfirmasiModal{{ $item->id }}" tabindex="-1" aria-labelledby="konfirmasiModalLabel{{ $item->id }}" aria-hidden="true">
                                                              <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                  <form action="{{ route('penjualan.konfirmasi', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header">
                                                                      <h5 class="modal-title" id="konfirmasiModalLabel{{ $item->id }}">Konfirmasi Pesanan</h5>
                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                      Apakah Anda yakin ingin mengkonfirmasi pesanan ini? Status akan berubah menjadi <b>Menunggu Kurir</b>.
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                      <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                                    </div>
                                                                  </form>
                                                                </div>
                                                              </div>
                                                            </div>
                                                        @endif
                                                    @elseif(auth()->user()->role == 'karyawan')
                                                        <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                        @if($item->status_order == 'Menunggu Kurir')
                                                            <button type="button" class="btn btn-success btn-sm rounded-pill ms-2" data-bs-toggle="modal" data-bs-target="#konfirmasiKurirModal{{ $item->id }}">
                                                                <i class="fas fa-check-circle me-1"></i> Konfirmasi Pesanan
                                                            </button>
                                                            <!-- Modal Pilih Kurir -->
                                                            <div class="modal fade" id="konfirmasiKurirModal{{ $item->id }}" tabindex="-1" aria-labelledby="konfirmasiKurirModalLabel{{ $item->id }}" aria-hidden="true">
                                                              <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                  <form action="{{ route('penjualan.konfirmasi', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-header">
                                                                      <h5 class="modal-title" id="konfirmasiKurirModalLabel{{ $item->id }}">Pilih Kurir</h5>
                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                      <div class="mb-3">
                                                                        <label for="kurir_select_{{ $item->id }}" class="form-label">Kurir</label>
                                                                        <select class="form-control" name="kurir_option" id="kurir_select_{{ $item->id }}" required onchange="setKurirFields{{ $item->id }}(this)">
                                                                            <option value="">Pilih Kurir</option>
                                                                            @php
                                                                            // Ambil data kurir dari controller (pastikan $kurirList dikirim dari controller)
                                                                            @endphp
                                                                            @foreach($kurirList ?? [] as $kurir)
                                                                            <option value="{{ $kurir->name }}|{{ $kurir->no_hp }}">{{ $kurir->name }} - {{ $kurir->no_hp }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                      </div>
                                                                      <input type="hidden" name="nama_kurir" id="nama_kurir_{{ $item->id }}">
                                                                      <input type="hidden" name="telpon_kurir" id="telpon_kurir_{{ $item->id }}">
                                                                      <div>
                                                                        Setelah konfirmasi, pengiriman akan otomatis dibuat dan status penjualan menjadi <b>Diproses</b>.
                                                                      </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                      <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                                    </div>
                                                                  </form>
                                                                </div>
                                                              </div>
                                                            </div>
                                                            <script>
                                                            function setKurirFields{{ $item->id }}(select) {
                                                                var val = select.value.split('|');
                                                                document.getElementById('nama_kurir_{{ $item->id }}').value = val[0] || '';
                                                                document.getElementById('telpon_kurir_{{ $item->id }}').value = val[1] || '';
                                                            }
                                                            </script>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('penjualan.show', $item->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                        <a href="{{ route('penjualan.edit', $item->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                        <form action="{{ route('penjualan.destroy', $item->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Hapus data penjualan ini?')">
                                                                <i class="fas fa-trash-alt me-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                        @if($item->status_order == 'Menunggu Konfirmasi' && auth()->user()->role == 'admin')
                                                            <button type="button" class="btn btn-success btn-sm rounded-pill ms-2" data-bs-toggle="modal" data-bs-target="#konfirmasiModal{{ $item->id }}">
                                                                <i class="fas fa-check-circle me-1"></i> Konfirmasi
                                                            </button>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="konfirmasiModal{{ $item->id }}" tabindex="-1" aria-labelledby="konfirmasiModalLabel{{ $item->id }}" aria-hidden="true">
                                                              <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                  <form action="{{ route('penjualan.konfirmasi', $item->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header">
                                                                      <h5 class="modal-title" id="konfirmasiModalLabel{{ $item->id }}">Konfirmasi Pesanan</h5>
                                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                      Apakah Anda yakin ingin mengkonfirmasi pesanan ini? Status akan berubah menjadi <b>Menunggu Kurir</b>.
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                      <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                                    </div>
                                                                  </form>
                                                                </div>
                                                              </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data penjualan</td>
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