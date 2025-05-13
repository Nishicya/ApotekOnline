@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ isset($user) ? 'Edit User' : 'Create User' }}</h4>
            <p class="card-description">{{ isset($user) ? 'Update user information' : 'Fill the form to create a new user' }}</p>
            <form class="forms-sample"
                    method="POST" 
                    action="{{ route('user.update', $user->id) }}" 
                    enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="exampleInputName1">Name</label>
                    <input type="text" 
                        class="form-control" 
                        id="name" 
                        name="name" 
                        placeholder="Name" 
                        value="{{ old('name', $user->name ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail3">Email address</label>
                    <input type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        placeholder="Email" 
                        value="{{ old('email', $user->email ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword4">Phone Number</label>
                    <input type="text" 
                        class="form-control" 
                        id="no_hp" 
                        name="no_hp" 
                        placeholder="Phone Number" 
                        value="{{ old('no_hp', $user->no_hp ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-select" id="role" name="role">
                        <option selected disabled>Select Role</option>
                        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pemilik" {{ old('role', $user->role ?? '') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                        <option value="apoteker" {{ old('role', $user->role ?? '') == 'apoteker' ? 'selected' : '' }}>Apoteker</option>
                        <option value="karyawan" {{ old('role', $user->role ?? '') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        <option value="kasir"{{ old('role', $user->role ?? '') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                    @error('role')
                        <span class="invalid-feedback" style="display: block;" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary me-2">Submit</button>
                <a href="{{ route('user.manage') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection