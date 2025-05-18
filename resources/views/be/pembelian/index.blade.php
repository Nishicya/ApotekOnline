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
                            <h4 class="card-title">Daftar Pembelian</h4>
                            @if(auth()->user()->role !== 'pemilik')
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('pembelian.create') }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-plus-circle me-2"></i>Tambah Penjualan
                                </a>
                            </div>
                            @endif
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No. Nota</th>
                                            <th>Tanggal</th>
                                            <th>Distributor</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pembelians as $pembelian)
                                        <tr>
                                            <td>{{ $pembelian->no_nota }}</td>
                                            <td>{{ date('d/m/Y', strtotime($pembelian->tgl_pembelian)) }}</td>
                                            <td>{{ $pembelian->distributor->nama_distributor }}</td>
                                            <td>Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(auth()->user()->role == 'pemilik')
                                                    <a href="{{ route('laporanpembelian.show', $pembelian->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    @endif
                                                    
                                                    @if(auth()->user()->role !== 'pemilik')
                                                    <a href="{{ route('pembelian.show', $pembelian->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    <a href="{{ route('pembelian.edit', $pembelian->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('pembelian.destroy', $pembelian->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Hapus pembelian ini?')">
                                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                    @endif
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