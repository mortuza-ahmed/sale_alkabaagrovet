@extends('backend.layouts.master')
@push('title')
    Purchase Show
@endpush
@push('css')
    <link href="{{ asset('storage/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="page-content">
        <div class="container-fluid mt-5">
            <div class="page-content-wrapper">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title m-0">Purchase Show</h4>
                                <a href="{{ route('purchases.index') }}"
                                    class="btn btn-sm btn-success waves-effect waves-light"><i class="fas fa-file"></i>
                                    Purchases</a>
                            </div>
                            <div class="card-body">
                                <div class="supplier_wrapper">
                                    <div class="row mb-2">

                                        <div class="col-4">
                                            <label class="form-label">Supplier Name</label>
                                            <input type="text" class="form-control form-control-sm" id="supplier_name"
                                                name="supplier_name" value="{{ $purchase->supplier->name ?? '' }}" readonly>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label">Supplier Phone</label>
                                            <input type="text" class="form-control form-control-sm" id="supplier_phone"
                                                name="supplier_phone" value="{{ $purchase->supplier->phone ?? '' }}" readonly>
                                        </div>
                                        <div class="col-4">
                                            <label for="purchase_date" class="form-label">Purchase Date</label>
                                            <input type="date" class="form-control form-control-sm" value="{{ $purchase->purchase_date ?? '' }}" id="purchase_date"
                                                name="purchase_date" required>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="purchase_wrapper my-4">
                                    <div class="row">
                                            <div class="col-6 col-sm-3 mb-2">
                                                <label for="">Product Name</label>
                                            </div>
                                            <div class="col-6 col-sm-3 mb-2">
                                                <label for="">Quantity</label>
                                            </div>
                                            <div class="col-6 col-sm-3 mb-2">
                                                <label for="">Buying Price</label>
                                            </div>
                                            <div class="col-6 col-sm-3 mb-2">
                                                <label for="">Subtotal</label>
                                            </div>
                                        </div>
                                    @foreach ($purchase->items as $item)
                                        <div class="row">
                                            <div class="col-6 col-sm-3 mb-2">
                                                <input type="text" class="form-control form-control-sm" value="{{ $item->product->name ?? '' }} {{ $item->product->unit?->name ?? '' }}" readonly>
                                            </div>
                                            <div class="col-6 col-sm-3 mb-2">
                                                <input type="text" class="form-control form-control-sm" value="{{ $item->quantity ?? '' }}" readonly>
                                            </div>
                                            <div class="col-6 col-sm-3 mb-2">
                                                <input type="text" class="form-control form-control-sm" value="{{ $item->buying_price ?? '' }}" readonly>
                                            </div>
                                            <div class="col-6 col-sm-3 mb-2">
                                                <input type="text" class="form-control form-control-sm" value="{{ $item->subtotal ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row my-2 d-flex justify-content-end">
                                    <div class="col-12 col-sm-3 d-flex justify-content-between align-items-center">
                                        <h3>Total Amount:</h3>
                                        <h4 class="total_amount">{{ $purchase->total_amount }}</h4>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('purchases.index') }}"
                                    class="btn btn-sm btn-success waves-effect waves-light"><i class="fas fa-file"></i>
                                    Purchases</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
