@extends('backend.layouts.master')
@push('title')
    Product create
@endpush
@push('css')
<link href="{{ asset('storage/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
       <div class="page-content">
                    <div class="container-fluid mt-5">
                        <div class="page-content-wrapper">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title m-0">Product Create</h4>
                                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-success waves-effect waves-light"><i class="fas fa-file"></i> Products</a>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('products.store') }}" method="POST">
                                                @csrf
                                                <div class="mb-2">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Enter name" title="Enter name here" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="unit_id" class="form-label">Unit</label>
                                                    <select name="unit_id" id="unit_id" class="form-control form-control-sm" required>
                                                        <option value="">Select Unit</option>
                                                        @foreach($units as $unit)
                                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="price" class="form-label">Price</label>
                                                    <input type="number"
                                                            class="form-control form-control-sm"
                                                            id="price"
                                                            name="price"
                                                            placeholder="Enter price here"
                                                            title="Enter price here" required>
                                                </div>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="reset" class="btn btn-sm btn-secondary waves-effect waves-light"><i class="fas fa-sync-alt"></i> Reset</button>
                                                    <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light"><i class="fas fa-save"></i> Submit</button>
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
@push('scripts')
    <script src="{{ asset('storage/assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#unit_id').select2({
                placeholder: "Select a unit",
                allowClear: true
            });
        });
    </script>

@endpush
