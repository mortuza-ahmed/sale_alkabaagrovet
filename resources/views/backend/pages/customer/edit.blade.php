@extends('backend.layouts.master')
@push('title')
    Customer Edit
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
                                            <h4 class="card-title m-0">Customer Edit</h4>
                                            <a href="{{ route('customers.index') }}" class="btn btn-sm btn-success waves-effect waves-light"><i class="fas fa-file"></i> Customers</a>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-2">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Enter name" title="Enter name here" value="{{ $customer->name }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="phone" class="form-label">Phone</label>
                                                     <input type="number"
                                                            class="form-control form-control-sm"
                                                            id="phone"
                                                            name="phone"
                                                            placeholder="Enter phone number here"
                                                            required
                                                            title="Enter phone number here"
                                                            value="{{ $customer->phone }}">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text" class="form-control form-control-sm" id="address" name="address" placeholder="Enter address" title="Enter address here" value="{{ $customer->address }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label for="sales_center" class="form-label">Sales Center</label>
                                                    <input type="text" class="form-control form-control-sm" id="sales_center" name="sale_center" value="{{ $customer->sale_center }}" placeholder="Enter Sales Center" title="Enter sale center here">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="district" class="form-label">District</label>
                                                    <input type="text" class="form-control form-control-sm" id="district" name="district" value="{{ $customer->district }}" placeholder="Enter district" title="Enter district here">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="thana" class="form-label">Thana</label>
                                                    <input type="text" class="form-control form-control-sm" id="thana" name="thana" value="{{ $customer->thana }}" placeholder="Enter thana" title="Enter thana here">
                                                </div>
                                                <div class="mb-2">
                                                    <label for="area" class="form-label">Area</label>
                                                    <input type="text" class="form-control form-control-sm" id="area" name="area" value="{{ $customer->area }}" placeholder="Enter area" title="Enter area here">
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

