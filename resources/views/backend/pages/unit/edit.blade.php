@extends('backend.layouts.master')
@push('title')
    Unit Edit
@endpush
@push('css')

@endpush
@section('content')
       <div class="page-content">
                    <div class="container-fluid mt-5">
                        <div class="page-content-wrapper">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title m-0">Unit Edit</h4>
                                            <a href="{{ route('units.index') }}" class="btn btn-sm btn-success waves-effect waves-light"><i class="fas fa-file"></i> Units</a>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('units.update', $unit->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-2">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Enter name" title="Enter name here" value="{{ $unit->name }}" required>
                                                </div>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="reset" class="btn btn-sm btn-secondary waves-effect waves-light"><i class="fas fa-sync-alt"></i> Reset</button>
                                                    <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light"><i class="fas fa-save"></i> Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection

