@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection

@section('content')
<div class="section">
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="section-title">
                    <h3 class="title">Profil Pengguna: {{ $user->name }}</h3>
                </div>
            </div>

            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="billing-details">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4">
                                <div class="p-3 mb-3" style="height: 100%;">
                                    <div class="d-flex flex-column align-items-center">
                                        @if($user->foto)
                                            <img src="{{ asset('storage/'.$user->foto) }}" 
                                                 class="img-thumbnail rounded-circle profile-image mb-3" 
                                                 style="width: 200px; height: 200px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center profile-image mb-3" 
                                                 style="width: 200px; height: 200px;">
                                                <i class="fa fa-user text-white" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                        <div class="form-group text-center">
                                            <label class="btn btn-primary" for="foto">
                                                <i class="fa fa-camera me-1"></i> Ganti Foto Profil
                                                <input type="file" id="foto" name="foto" class="d-none" onchange="previewImage(this)">
                                                @error('foto')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="p-3" style="height: 100%;">
                                    <div class="form-group">
                                        <label for="name">Nama Lengkap</label>
                                        <input class="input" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input class="input" type="email" id="email" name="email"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="no_hp">No. HP</label>
                                        <input class="input" type="text" id="no_hp" name="no_hp"
                                               value="{{ old('no_hp', $user->no_hp) }}" required>
                                        @error('no_hp')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="aktif">Status Akun</label>
                                        <select class="input" id="aktif" name="aktif" required>
                                            <option value="1" {{ $user->aktif == 1 ? 'selected' : '' }}>Aktif</option>
                                            <option value="0" {{ $user->aktif == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <input class="input" type="text" id="role" name="role"
                                               value="{{ $user->role }}" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                                        <input class="input" type="password" id="password" name="password">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="text-right mt-3">
                                        <button type="submit" class="primary-btn">
                                            <i class="fa fa-save"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.querySelector('.profile-image');
                if (img.tagName === 'IMG') {
                    img.src = e.target.result;
                } else {
                    // Jika placeholder div, ubah menjadi img
                    const parent = img.parentNode;
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.className = 'img-thumbnail rounded-circle profile-image mb-3';
                    newImg.style.width = '200px';
                    newImg.style.height = '200px';
                    newImg.style.objectFit = 'cover';
                    parent.replaceChild(newImg, img);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection