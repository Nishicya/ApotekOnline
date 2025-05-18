@extends('fe.master')

@section('header')
    @include('fe.header')
@endsection

@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<!-- CONTACT SECTION -->
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="section-title">
                    <h2 class="title">Hubungi Kami</h2>
                </div>
                <p><strong>Alamat:</strong> 1734 Stonecoal Road</p>
                <p><strong>Email:</strong> healthify@gmail.com</p>
                <p><strong>Telepon:</strong> +021-95-51-84</p>
                <p>Kami siap melayani Anda setiap hari kerja pukul 06:00 - 22:00. Jangan ragu untuk menghubungi kami jika ada pertanyaan tentang produk atau pesanan Anda.</p>
            </div>

            <div class="col-md-6">
                <form>
                    <div class="form-group mb-3">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" id="name" placeholder="Nama Anda">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Email Anda">
                    </div>
                    <div class="form-group mb-3">
                        <label for="message">Pesan</label>
                        <textarea class="form-control" id="message" rows="5" placeholder="Tulis pesan Anda..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Kirim Pesan</button>
                </form>
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
