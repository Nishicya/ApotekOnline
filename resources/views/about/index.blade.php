@extends('fe.master')

@section('header')
    @include('fe.header')
@endsection

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<!-- ABOUT SECTION -->
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2 class="title">Tentang Healthify</h2>
                </div>
                <p style="font-size: 1.5rem;">
                    <strong>Healthify</strong> adalah apotek online terpercaya yang menyediakan obat-obatan, suplemen, alat kesehatan, dan kebutuhan medis lainnya. Kami hadir untuk memberikan kemudahan dalam mengakses produk kesehatan dari rumah Anda, dengan sistem pemesanan yang cepat dan aman.
                </p>
                <p style="font-size: 1.5rem;">
                    Dengan dukungan tenaga farmasi profesional dan teknologi terkini, Healthify memastikan bahwa setiap produk yang Anda pesan terjamin keasliannya dan sampai ke tangan Anda dengan aman. Kami juga menyediakan fitur resep digital untuk mempermudah Anda mendapatkan obat keras sesuai resep dokter.
                </p>
                <p style="font-size: 1.5rem;">
                    Misi kami adalah meningkatkan kualitas hidup masyarakat dengan menghadirkan solusi kesehatan yang mudah diakses, terjangkau, dan terpercaya.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('newsletter')
    @include('fe.newsletter')
@endsection

@section('footer')
    @include('fe.footer')
@endsection
