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
                            <h4 class="card-title">{{$title}}</h4>
                            <div class="table-responsive">
                            <div class="d-flex justify-content-start mb-3">
                                <a href="{{ route('user.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus-circle me-2"></i>Add New User
                                </a>
                            </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($users as $nmr => $data)
                                        <tr>
                                            <th scope="row">{{ $nmr + 1 }}.</th>
                                            <td>
                                                @if (strlen($data['name']) > 10)
                                                    
                                                    {{ substr($data['name'], 0, 10) . '...' }}
                                                @else
                                                    {{ $data['name'] }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ ucfirst($data['role']) }}
                                                @if ($data['role'] === 'admin' || $data['role'] === 'apoteker' || $data['role'] === 'owner' || $data['role'] === 'karyawan'|| $data['role'] === 'kasir')
                                                    @php
                                                        $level = \App\Models\User::where('id', $data->id)->first()?->role;
                                                    @endphp 
                                                @endif
                                            </td>
                                            <td>
                                                @if (strlen($data['email']) > 10)
                                                    
                                                    {{ substr($data['email'], 0, 15) . '...' }}
                                                @else
                                                    {{ $data['email'] }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ strlen($data['no_hp']) > 5 ? substr($data['no_hp'], 0, 10) . '...' : $data['no_hp'] }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('user.edit', $data->id) }}" class="btn btn-light btn-sm">
                                                        <i class="fa fa-pencil-square-o"></i> Edit
                                                    </a>
                                                    <form action="{{ route('user.destroy', $data->id) }}" method="POST" id="deleteForm{{ $data->id }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-primary btn-fw" onclick="deleteConfirm({{ $data->id }})">
                                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data pelanggan.</td>
                                            </tr>
                                        @endforelse
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