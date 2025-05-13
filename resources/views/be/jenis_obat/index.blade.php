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
                                <a href="{{ route('jenis-obat.create') }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-plus-circle me-2"></i>Add Medicine Type
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Image</th>
                                            <th>Type Name</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jenisObats as $nmr => $jenis)
                                        <tr>
                                            <td>{{ $nmr + 1 }}.</td>
                                            <td>
                                                @if($jenis->image_url)
                                                    <img src="{{ asset('storage/' . $jenis->image_url) }}" width="50" class="rounded">
                                                @else
                                                    <span class="text-muted">No image</span>
                                                @endif
                                            </td>
                                            <td>{{ $jenis->jenis }}</td>
                                            <td>{{ Str::limit($jenis->deskripsi_jenis, 30) }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('jenis-obat.show', $jenis->id) }}" class="btn btn-info btn-sm rounded-pill me-2">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    <a href="{{ route('jenis-obat.edit', $jenis->id) }}" class="btn btn-light btn-sm rounded-pill me-2">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('jenis-obat.destroy', $jenis->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Delete this medicine type?')">
                                                            <i class="fas fa-trash-alt me-1"></i> Delete
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
@endsection

@section('script')
<script>
  function confirmDelete(id) {
      if (confirm('Are you sure you want to delete this medicine type?')) {
          document.getElementById('deleteForm'+id).submit();
      }
  }
</script>
@endsection