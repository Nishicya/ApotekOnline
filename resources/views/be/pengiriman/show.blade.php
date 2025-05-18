@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="container-fluid page-body-wrapper">
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-8 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Detail Pengiriman</h4>
                            <table class="table">
                                <tr>
                                    <th>No Invoice</th>
                                    <td>{{ $pengiriman->no_invoice }}</td>
                                </tr>
                                <tr>
                                    <th>Penjualan</th>
                                    <td>#{{ $pengiriman->penjualan->id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kirim</th>
                                    <td>{{ $pengiriman->tgl_kirim }}</td>
                                </tr>
                                <tr>
                                    <th>Status Kirim</th>
                                    <td>{{ $pengiriman->status_kirim }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Kurir</th>
                                    <td>{{ $pengiriman->nama_kurir }}</td>
                                </tr>
                                <tr>
                                    <th>Telpon Kurir</th>
                                    <td>{{ $pengiriman->telpon_kurir }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $pengiriman->keterangan }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection