@extends('backend.layouts.master')
@push('title')
    Purchases Print
@endpush
@push('css')
    <style>
        .table-borderless,
        .table-borderless tr,
        .table-borderless td,
        .table-borderless th {
            border: none !important;
            padding:0px;
        }
    </style>
@endpush
@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="card px-2 py-3" id="invoice">
            {{-- HEADER --}}
             <div class="d-flex justify-content-center align-items-center">
                <div>
                    @if(auth()->user()->company_logo)
                        <img src="{{ asset(auth()->user()->company_logo) }}"
                            alt="Company Logo"
                            style="max-height: 90px;">
                    @endif
                </div>
                <div class="text-center">
                    <h4 class="fw-bold text-success">{{ auth()->user()->company_name ?? 'Company Name Here' }}</h4>
                    <p class="mb-0">{!! auth()->user()->company_address ?? 'Company Address Here' !!}</p>
                    <p class="mb-0">{{ auth()->user()->company_phone ?? 'Company Phone Here' }}</p>
                    <h6 class=" text-uppercase">Invoice</h6>
                </div>
            </div>
            {{-- Supplier INFO --}}
            <table class="table table-bordered mb-1" style="border:1px solid #ADADAD;">
                <tr class="">
                    <td class="w-50 p-1">
                        <div class="d-flex justify-content-between">

                            <!-- LEFT SIDE -->
                            <table class="table table-borderless table-sm w-auto mb-0">
                                <tr>
                                    <td class="fw-bold pe-2">Supplier Name & ID</td>
                                    <td class="pe-2">:</td>
                                    <td>{{ $purchase->supplier?->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Supplier Address</td>
                                    <td>:</td>
                                    <td>{{ $purchase->supplier?->address }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Mobile</td>
                                    <td>:</td>
                                    <td>{{ $purchase->supplier?->phone }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td class="w-50 p-1">

                        <!-- RIGHT SIDE -->
                        <div class="d-flex justify-content-start">
                            <table class="table table-borderless table-sm w-auto mb-0">
                                <tr>
                                    <td class="fw-bold pe-2">Name & ID</td>
                                    <td class="pe-2">:</td>
                                    <td>{{ $purchase->supplier?->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Payment Type</td>
                                    <td class="pe-2">:</td>
                                    <td>Bank</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Purchase No</td>
                                    <td class="pe-2">:</td>
                                    <td>#{{ $purchase->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Purchase Date</td>
                                    <td class="pe-2">:</td>
                                    <td>{{ Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- ITEMS TABLE --}}
            <table class="table table-bordered mb-2" style="border:1px solid #ADADAD">
                <thead class="table-secondary p-0">
                    <tr class="p-0">
                        <th class="p-1">SL</th>
                        <th class="p-1">Description of Goods</th>
                        <th class="p-1">Qty</th>
                        <th class="p-1">Buying Price</th>
                        <th class="p-1">Amount</th>
                    </tr>
                </thead>
                <tbody class="p-0">
                    @foreach($purchase->items as $key => $item)
                    <tr class="p-0">
                        <td style="padding:0px 2px;">{{ $key + 1 }}</td>
                        <td style="padding:0px 2px;">{{ $item->product->name }} {{ $item->product?->unit?->name }}</td>
                        <td style="padding:0px 2px;">{{ $item->quantity }}</td>
                        <td style="padding:0px 2px;">{{ number_format($item->buying_price,2) }}</td>
                        <td style="padding:0px 2px;">{{ number_format($item->subtotal,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end" style="padding:0px 5px;">Total Amount<br> Paid Amount<br> Due Amount</th>
                        <th  style="padding:0px 5px;">{{ number_format($purchase->total_amount,2) }} <br> {{ number_format($purchase->total_amount,2) }} <br> {{ number_format(0,2) }}</th>

                    </tr>
                </tfoot>
            </table>
             {{-- AMOUNT IN WORDS --}}
            <p>
                <strong>Taka (In Words):</strong>
                {{ Str::ucfirst($words) }} taka only
            </p>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script>
    window.addEventListener('load', function() {
        window.print();
    });
</script>
@endpush
