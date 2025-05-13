@extends('be.master')
@section('navbar')
    @include('be.navbar')
@endsection
@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{$title}}</h4>
            <form class="forms-sample" action="{{ route('jenis-obat.update', $jenisObat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="jenis">Type Name</label>
                    <input type="text" class="form-control" id="jenis" name="jenis" 
                           placeholder="Type name" value="{{ $jenisObat->jenis }}">
                    @error('jenis')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="deskripsi_jenis">Description</label>
                    <textarea class="form-control" id="deskripsi_jenis" name="deskripsi_jenis" 
                              rows="4" placeholder="Type description">{{ $jenisObat->deskripsi_jenis }}</textarea>
                    @error('deskripsi_jenis')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Type Image</label>
                    <div class="input-group">
                        <input type="file" class="form-control" name="image_url" id="image_url">
                    </div>
                    @error('image_url')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    @if(isset($jenisObat) && $jenisObat->image_url)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $jenisObat->image_url) }}" width="100" class="img-thumbnail">
                            <p class="text-muted mt-1">Current Image</p>
                        </div>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary me-2">Update</button>
                <a href="{{ route('jenis-obat.manage') }}" class="btn btn-light">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection