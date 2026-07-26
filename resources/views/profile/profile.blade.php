@extends('backend.layouts.master')
@push('title')
    My Profile
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <div class="page-title">
                        <h4 class="mb-0 font-size-18">My Profile</h4>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">Welcome to profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-wrapper">
            <div class="row">
                <div class="col-xl-6 col-md-6 col-12">
                    <div class="card bg-defalut mini-stat position-relative">
                        <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form action="{{ route('profile') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}">
                                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}">
                                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="company_name" class="form-label">Company Name</label>
                                        <input type="text" id="company_name" name="company_name" class="form-control" value="{{ $user->company_name }}">
                                        @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="company_phone" class="form-label">Compnay Phone</label>
                                        <input type="number" id="company_phone" name="company_phone" class="form-control" value="{{ $user->company_phone }}">
                                        @error('company_phone') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="company_address" class="form-label">Company Address</label>
                                        <input type="text" id="company_address" name="company_address" class="form-control" value="{{ $user->company_address }}">
                                        @error('company_address') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="company_logo" class="form-label">Company Logo</label>
                                        <input type="file" id="company_logo" name="company_logo" class="form-control">
                                        @error('company_logo') <span class="text-danger">{{ $message }}</span> @enderror
                                        @if($user->company_logo)
                                            <div class="mt-2">
                                                <img src="{{ asset($user->company_logo) }}" alt="Logo" style="max-height: 80px;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                                        <input type="password" id="password" name="password" class="form-control">
                                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


</div>
@endsection
