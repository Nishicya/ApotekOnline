@extends('fe.master')

@section('header')
    @include('fe.header')
@endsection
@section('navbar')
    @include('fe.navbar')
@endsection

@section('content')
<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- section title -->
            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Profile {{ $pelanggan->nama_pelanggan ?? 'Guest' }}</h3>
                </div>
            </div>
            <!-- /section title -->

            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="billing-details">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    @if($pelanggan->foto)
                                        <img src="{{ asset('storage/'.$pelanggan->foto) }}" 
                                            class="img-thumbnail rounded-circle profile-image mb-3" 
                                            style="width: 200px; height: 200px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center profile-image mb-3" 
                                            style="width: 200px; height: 200px;">
                                            <i class="fa fa-user text-white" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="btn btn-primary mb-0">
                                        <i class="fa fa-camera me-1"></i> Ganti Foto Profil
                                        <input type="file" id="foto" name="foto" class="d-none" onchange="previewImage(this)">
                                    </label>
                                </div>
                                <div class="form-group mt-3">
                                    @if($pelanggan->url_ktp)
                                        <img src="{{ asset('storage/'.$pelanggan->url_ktp) }}" 
                                            class="img-thumbnail" 
                                            style="width: 100%; max-height: 200px; object-fit: contain;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                            style="height: 200px;">
                                            <i class="fa fa-id-card text-white" style="font-size: 3rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="btn btn-primary mb-0">
                                        <i class="fa fa-upload me-1"></i> Upload KTP
                                        <input type="file" id="url_ktp" name="url_ktp" class="d-none" onchange="previewKTP(this)">
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="nama_pelanggan">Nama Lengkap</label>
                                    <input class="input" type="text" id="nama_pelanggan" name="nama_pelanggan" 
                                           value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input class="input" type="email" id="email" name="email" 
                                           value="{{ old('email', $pelanggan->email) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="no_hp">No. Telepon/HP</label>
                                    <input class="input" type="text" id="no_hp" name="no_hp" 
                                           value="{{ old('no_hp', $pelanggan->no_hp) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                                    <input class="input" type="password" id="password" name="password">
                                </div>
                                
                                <div class="section-title" style="margin-top: 30px;">
                                    <h4 class="title">Alamat Utama</h4>
                                </div>
                                
                                <div class="form-group">
                                    <label for="alamat1">Alamat Lengkap</label>
                                    <textarea class="input" id="alamat1" name="alamat1" required>{{ old('alamat1', $pelanggan->alamat1) }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kota1">Kota</label>
                                            <input class="input" type="text" id="kota1" name="kota1" 
                                                   value="{{ old('kota1', $pelanggan->kota1) }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="propinsi1">Provinsi</label>
                                            <input class="input" type="text" id="propinsi1" name="propinsi1" 
                                                   value="{{ old('propinsi1', $pelanggan->propinsi1) }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kodepos1">Kode Pos</label>
                                            <input class="input" type="text" id="kodepos1" name="kodepos1" 
                                                   value="{{ old('kodepos1', $pelanggan->kodepos1) }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="section-title" style="margin-top: 30px;">
                                    <h4 class="title">Alamat Alternatif 1</h4>
                                </div>
                                
                                <div class="form-group">
                                    <label for="alamat2">Alamat Lengkap</label>
                                    <textarea class="input" id="alamat2" name="alamat2">{{ old('alamat2', $pelanggan->alamat2) }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kota2">Kota</label>
                                            <input class="input" type="text" id="kota2" name="kota2" 
                                                   value="{{ old('kota2', $pelanggan->kota2) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="propinsi2">Provinsi</label>
                                            <input class="input" type="text" id="propinsi2" name="propinsi2" 
                                                   value="{{ old('propinsi2', $pelanggan->propinsi2) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kodepos2">Kode Pos</label>
                                            <input class="input" type="text" id="kodepos2" name="kodepos2" 
                                                   value="{{ old('kodepos2', $pelanggan->kodepos2) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="section-title" style="margin-top: 30px;">
                                    <h4 class="title">Alamat Alternatif 2</h4>
                                </div>
                                
                                <div class="form-group">
                                    <label for="alamat3">Alamat Lengkap</label>
                                    <textarea class="input" id="alamat3" name="alamat3">{{ old('alamat3', $pelanggan->alamat3) }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kota3">Kota</label>
                                            <input class="input" type="text" id="kota3" name="kota3" 
                                                   value="{{ old('kota3', $pelanggan->kota3) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="propinsi3">Provinsi</label>
                                            <input class="input" type="text" id="propinsi3" name="propinsi3" 
                                                   value="{{ old('propinsi3', $pelanggan->propinsi3) }}">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="kodepos3">Kode Pos</label>
                                            <input class="input" type="text" id="kodepos3" name="kodepos3" 
                                                   value="{{ old('kodepos3', $pelanggan->kodepos3) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pull-right">
                            <button type="submit" class="primary-btn">
                                <i class="fa fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = input.closest('.col-md-4').querySelector('img');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const divPreview = input.closest('.col-md-4').querySelector('div');
                    divPreview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail rounded-circle" 
                                            style="width: 150px; height: 150px; object-fit: cover;">`;
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function previewKTP(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const preview = input.closest('.col-md-4').querySelectorAll('img')[1];
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const divPreview = input.closest('.col-md-4').querySelectorAll('div')[1];
                    divPreview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" 
                                            style="width: 100%; max-height: 200px; object-fit: contain;">`;
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    document.getElementById('foto').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        const preview = document.querySelector('.profile-image');
        preview.src = URL.createObjectURL(file);
    }
});


document.addEventListener('DOMContentLoaded', function() {
    // Debug: Check if SweetAlert is loaded
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded!');
        return;
    }

    // Debug: Check session data
    console.log('Session swal data:', @json(session('swal')));
    console.log('Validation errors:', @json($errors->all()));

    // Show SweetAlert notification if exists
    @if(session('swal'))
        Swal.fire({
            position: 'top-end',
            icon: '{{ session('swal.icon') }}',
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}',
            showConfirmButton: false,
            timer: {{ session('swal.timer') ?? 1500 }},
            toast: true
        });
    @endif
    
    // Show validation errors if any
    @if($errors->any())
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Validasi Error',
            html: `
                <ul class="text-left">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
            showConfirmButton: false,
            timer: 4000,
            toast: true
        });
    @endif
});
</script>
@endsection

@section('footer')
    @include('fe.footer')
@endsection