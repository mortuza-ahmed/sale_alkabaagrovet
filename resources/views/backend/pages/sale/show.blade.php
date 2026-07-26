@extends('backend.layouts.master')
@push('title')
    Sale Show
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
                            <h4 class="card-title m-0">Show Sale</h4>
                            <a href="{{ route('sales.index') }}" class="btn btn-sm btn-success waves-effect waves-light">
                                <i class="fas fa-file"></i> Sales
                            </a>
                        </div>
                        <div class="card-body">

                                <!-- Customer Row -->
                                <div class="row mb-2 align-items-end">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Customer Name</label>
                                        <input type="text" class="form-control form-control-sm" id="customer_name" value="{{ $sale->customer?->name ?? '' }}" readonly>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Customer Phone</label>
                                        <input type="text" class="form-control form-control-sm" id="customer_phone" value="{{ $sale->customer?->phone ?? '' }}" readonly>
                                    </div>
                                </div>

                                <!-- Order & Delivery Dates -->
                                <div class="row mt-2">
                                    <div class="col-12 col-sm-6">
                                        <label for="order_date" class="form-label">Order Date</label>
                                        <input type="date" class="form-control form-control-sm" name="order_date" value="{{ $sale->order_date }}">
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <label for="delivery_date" class="form-label">Delivery Date</label>
                                        <input type="date" class="form-control form-control-sm" name="delivery_date" value="{{ $sale->delivery_date }}">
                                    </div>
                                </div>

                                <hr>

                                <!-- Sale Items -->
                                <div class="sale_wrapper my-4">
                                    @foreach($sale->items as $index => $item)
                                        <div class="row mb-2 align-items-end sale_item_row">
                                            <div class="col-6 col-md-4 col-lg-3 p-1">
                                                <label>Product</label>
                                                <input type="text" class="form-control form-control-sm" value="{{ $item->product?->name }} {{ $item->product?->unit?->name }}" readonly>
                                            </div>
                                            <div class="col-6 col-md-3 col-lg-1 p-1">
                                                <label>Does</label>
                                                <input type="number" class="form-control form-control-sm" value="{{ $item->does_form }}" readonly>
                                            </div>
                                            <div class="col-6 col-md-3 col-lg-1 p-1">
                                                <label>Qty</label>
                                                <input type="number" class="form-control form-control-sm" value="{{ $item->quantity }}" readonly>
                                            </div>
                                            <div class="col-6 col-md-2 col-lg-1 p-1">
                                                <label>Bonus</label>
                                                <input type="number" class="form-control form-control-sm" value="{{ $item->bonus }}" readonly>
                                            </div>
                                            <div class="col-6 col-md-3 col-lg-2 p-1">
                                                <label>Price</label>
                                                <input type="number" class="form-control form-control-sm" value="{{ $item->price }}" readonly>
                                            </div>
                                            <div class="col-6 col-md-2 col-lg-1 p-1">
                                                 <label>Disc (%)</label>
                                                <input type="number" class="form-control form-control-sm" value="{{ $item->discount }}" readonly>
                                            </div>
                                            <div class="col-6 col-md-3 col-lg-2 p-1">
                                                <label>Subtotal</label>
                                                <input type="number" class="form-control form-control-sm" value="{{ $item->sub_total }}" readonly>
                                            </div>
                                            <div class="col-6 col-md-3 col-lg-1 p-1">
                                                <label>Facility</label>
                                                <input type="number" class="form-control form-control-sm" value="{{ $item->bonus_facility }}" readonly>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Total, Paid, Due -->
                                 <!-- Total, Paid, Due -->
                                <div class="row my-2 d-flex justify-content-end">
                                    <div class="col-12 col-sm-3 d-flex justify-content-between align-items-center">
                                        <h6>Amount:</h6><h6 class="total_amount">{{ number_format($sale->total) }}</h6>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex justify-content-end">
                                    <div class="col-12 col-sm-3 d-flex justify-content-between align-items-center">
                                        <h6>Discount:</h6><h6 class="total_amount">
                                            @if ($sale->discount_type == 'fixed')
                                                {{ $sale->discount_amount }}
                                            @elseif($sale->discount_type == 'percentage')
                                                {{ number_format($sale->discount_value) . '% ('.$sale->discount_amount.')' }}
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex justify-content-end">
                                    <div class="col-12 col-sm-3 d-flex justify-content-between align-items-center">
                                        <h6>Total Amount:</h6><h6 class="total_amount">{{ number_format($sale->grand_total) }}</h6>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex justify-content-end">
                                    <div class="col-12 col-sm-3 d-flex justify-content-between align-items-center">
                                        <h6>Paid Amount:</h6><h6 class="paid_amount">{{ number_format($sale->payment_transactions?->first()->paid) }}</h6>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex justify-content-end">
                                    <div class="col-12 col-sm-3 d-flex justify-content-between align-items-center">
                                        <h6>Due Amount:</h6><h6 class="due_amount">{{ number_format($sale->grand_total - $sale->payment_transactions?->first()->paid) }}</h6>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('sales.index') }}" class="btn btn-sm btn-success waves-effect waves-light">
                                        <i class="fas fa-file"></i> Sales
                                    </a>
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
