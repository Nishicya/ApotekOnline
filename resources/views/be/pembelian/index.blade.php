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
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('pembelian.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus-circle me-2"></i>Tambah Pembelian
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Nota</th>
                                            <th>Tanggal</th>
                                            <th>Distributor</th>
                                            <th>Total</th>
                                            <th>Detail</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pembelians as $index => $pembelian)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $pembelian->no_nota }}</td>
                                            <td>{{ $pembelian->tgl_pembelian->format('d/m/Y') }}</td>
                                            <td>{{ $pembelian->distributor->nama }}</td>
                                            <td>Rp{{ number_format($pembelian->total_bayar, 0, ',', '.') }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm view-detail" 
                                                        data-id="{{ $pembelian->id }}">
                                                    <i class="fa fa-eye"></i> Lihat
                                                </button>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('pembelian.edit', $pembelian->id) }}" 
                                                       class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('pembelian.destroy', $pembelian->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('Hapus data pembelian ini?')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
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

<!-- Modal for detail view -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Pembelian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>No. Nota:</strong> <span id="detail-nota"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Tanggal:</strong> <span id="detail-tanggal"></span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Distributor:</strong> <span id="detail-distributor"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Total:</strong> <span id="detail-total"></span>
                    </div>
                </div>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Obat</th>
                            <th>Jumlah</th>
                            <th>Harga Beli</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detail-obat">
                        <!-- Detail obat akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle detail view
    $('.view-detail').click(function() {
        const id = $(this).data('id');
        
        $.get(`/pembelian/${id}`, function(data) {
            $('#detail-nota').text(data.no_nota);
            $('#detail-tanggal').text(data.tgl_pembelian);
            $('#detail-distributor').text(data.distributor.nama);
            $('#detail-total').text('Rp' + new Intl.NumberFormat('id-ID').format(data.total_bayar));
            
            let detailHtml = '';
            data.detail_pembelians.forEach((detail, index) => {
                detailHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${detail.obat.nama_obat}</td>
                        <td>${detail.jumlah}</td>
                        <td>Rp${new Intl.NumberFormat('id-ID').format(detail.harga_beli)}</td>
                        <td>Rp${new Intl.NumberFormat('id-ID').format(detail.subtotal)}</td>
                    </tr>
                `;
            });
            $('#detail-obat').html(detailHtml);
            
            $('#detailModal').modal('show');
        });
    });
});
</script>
@endsection