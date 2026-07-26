@extends('backend.layouts.master')
@push('title')
    Purchase Edit
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
                                <h4 class="card-title m-0">Edit Purchase</h4>
                                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-success waves-effect waves-light">
                                    <i class="fas fa-file"></i> Purchases
                                </a>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Supplier and Date -->
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <label for="supplier_id" class="form-label">Supplier Select</label>
                                            <select name="supplier_id" id="supplier_id" class="form-control form-control-sm" required>
                                                <option value="">Select Supplier</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        data-supplierName="{{ $supplier->name }}"
                                                        data-supplierPhone="{{ $supplier->phone }}"
                                                        {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label for="purchase_date" class="form-label">Purchase Date</label>
                                            <input type="date" class="form-control form-control-sm" id="purchase_date"
                                                name="purchase_date" value="{{ $purchase->purchase_date }}" required>
                                        </div>
                                    </div>

                                    <!-- Supplier Info -->
                                    <div class="supplier_wrapper">
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <label class="form-label">Supplier Name</label>
                                                <input type="text" class="form-control form-control-sm" id="supplier_name"
                                                    name="supplier_name" value="{{ $purchase->supplier->name ?? '' }}" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Supplier Phone</label>
                                                <input type="text" class="form-control form-control-sm" id="supplier_phone"
                                                    name="supplier_phone" value="{{ $purchase->supplier->phone ?? '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <!-- Purchase Items -->
                                    <div class="purchase_wrapper my-4">
                                        <!-- Add Item Button -->
                                        <div class="row mb-2 d-flex justify-content-end">
                                            <div class="col-12 col-sm-2">
                                                <a href="javascript:;" class="btn btn-sm btn-success w-100 add-item">
                                                    <i class="fas fa-plus"></i> Add
                                                </a>
                                            </div>
                                        </div>
                                        @foreach ($purchase->items as $item)
                                        <div class="row mb-2">
                                            <div class="col-6 col-md-4 mb-2">
                                                <select name="product_id[]" class="product_id form-control form-control-sm" required>
                                                    <option value="">Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                            {{ $product->name }} {{ $product->unit?->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6 col-md-2 mb-2">
                                                <input type="number" class="form-control form-control-sm" name="quantity[]"
                                                    value="{{ $item->quantity }}" placeholder="Enter quantity here" required>
                                            </div>
                                            <div class="col-6 col-md-2 mb-2">
                                                <input type="number" class="form-control form-control-sm" name="buying_price[]"
                                                    value="{{ $item->buying_price }}" placeholder="Enter buying price here" required>
                                            </div>
                                            <div class="col-6 col-md-2 mb-2">
                                                <input type="number" class="form-control form-control-sm item_subtotal"
                                                    name="subtotal[]" value="{{ $item->quantity * $item->buying_price }}" readonly>
                                            </div>
                                            <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                                                <a href="javascript:;" class="btn btn-sm btn-danger w-100 remove-item">
                                                    <i class="fas fa-trash"></i> Remove
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach

                                        <div class="purchase_list"></div>


                                    </div>

                                    <!-- Total Amount -->
                                    <div class="row my-2 d-flex justify-content-end">
                                        <div class="col-12 col-sm-3 d-flex justify-content-between align-items-center">
                                            <h3>Total Amount:</h3><h4 class="total_amount">0.00</h4>
                                        </div>
                                    </div>

                                    <!-- Form Buttons -->
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="reset" class="btn btn-sm btn-secondary waves-effect waves-light">
                                            <i class="fas fa-sync-alt"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-primary waves-effect waves-light">
                                            <i class="fas fa-save"></i> Update
                                        </button>
                                    </div>

                                </form>
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

    // Initialize select2
    $('#supplier_id').select2({ placeholder: "Select a supplier", allowClear: true });
    $('.product_id').select2({ placeholder: "Select a product", allowClear: true });

    // Function to calculate subtotal per row and total
    function calculateTotal() {
        let total = 0;
        $('.purchase_wrapper .row').each(function() {
            let qty = parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
            let price = parseFloat($(this).find('input[name="buying_price[]"]').val()) || 0;
            let subtotal = qty * price;
            $(this).find('input.item_subtotal').val(subtotal);
            total += subtotal;
        });
        $('.total_amount').text(total.toFixed(2));
    }

    // Initial total calculation
    calculateTotal();

    // Supplier change event
    $('#supplier_id').on('change', function() {
        let selectedOption = $(this).find('option:selected');
        let supplierName = selectedOption.data('suppliername');
        let supplierPhone = selectedOption.data('supplierphone');
        $('.supplier_wrapper').html(`
            <div class="row mb-2">
                <div class="col-6">
                    <label class="form-label">Supplier Name</label>
                    <input type="text" class="form-control form-control-sm" id="supplier_name" name="supplier_name" value="${supplierName ?? ''}" readonly>
                </div>
                <div class="col-6">
                    <label class="form-label">Supplier Phone</label>
                    <input type="text" class="form-control form-control-sm" id="supplier_phone" name="supplier_phone" value="${supplierPhone ?? ''}" readonly>
                </div>
            </div>
        `);
    });

    // Calculate subtotal on quantity or price change
    $(document).on('input', 'input[name="quantity[]"], input[name="buying_price[]"]', function() {
        calculateTotal();
    });

    // Add new purchase item
    $(document).on('click', '.add-item', function () {
        let html = `
            <div class="row mb-2">
                <div class="col-6 col-md-4 mb-2">
                    <select name="product_id[]" class="product_id form-control form-control-sm" required>
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} {{ $product->unit?->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2 mb-2">
                    <input type="number" class="form-control form-control-sm" name="quantity[]" placeholder="Enter quantity here" required>
                </div>
                <div class="col-6 col-md-2 mb-2">
                    <input type="number" class="form-control form-control-sm" name="buying_price[]" placeholder="Enter buying price here" required>
                </div>
                <div class="col-6 col-md-2 mb-2">
                    <input type="number" class="form-control form-control-sm item_subtotal" name="subtotal[]" placeholder="0" readonly>
                </div>
                <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                    <a href="javascript:;" class="btn btn-sm btn-danger w-100 remove-item"><i class="fas fa-trash"></i> Remove</a>
                </div>
            </div>
        `;
        $('.purchase_list').append(html);
        $('.product_id').select2({ placeholder: "Select a product", allowClear: true });
    });

    // Remove item
    $(document).on('click', '.remove-item', function () {
        $(this).closest('.row').remove();
        calculateTotal();
    });

});
</script>
@endpush
