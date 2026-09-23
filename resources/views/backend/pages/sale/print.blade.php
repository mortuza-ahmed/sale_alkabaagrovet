@extends('backend.layouts.master')
@push('title')
    Sales Print
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

        .print-footer-note {
            text-align: left;
            font-size: 12px;
            margin-top: 15px;
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
                            style="max-height: 100px;">
                    @endif
                </div>
                <div class="text-center">
                    <h3 class="fw-bold text-success">{{ auth()->user()->company_name ?? 'Company Name Here' }}</h3>
                    <p class="mb-0">{!! auth()->user()->company_address ?? 'Company Address Here' !!}</p>
                    <p class="mb-0">{{ auth()->user()->company_phone ?? 'Company Phone Here' }}</p>
                    <h6 class=" text-uppercase">Invoice</h6>
                </div>
            </div>
            {{-- CUSTOMER INFO --}}
            <table class="table table-bordered mb-1" style="border:1px solid #ADADAD;">
                <tr class="">
                    <td class="w-50 p-1">
                        <div class="d-flex justify-content-between">

                            <!-- LEFT SIDE -->
                            <table class="table table-borderless table-sm w-auto mb-0">
                                <tr>
                                    <td class="fw-bold pe-2">Customer Name & ID</td>
                                    <td class="pe-2">:</td>
                                    <td>{{ $sale->customer?->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Customer Address</td>
                                    <td>:</td>
                                    <td>{{ $sale->customer?->address }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Thana Name</td>
                                    <td>:</td>
                                    <td>{{ $sale->customer?->thana }}</td>
                                </tr>
                                 <tr>
                                    <td class="fw-bold pe-2">District Name</td>
                                    <td>:</td>
                                    <td>{{ $sale->customer?->district }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Sales Center Name</td>
                                    <td>:</td>
                                    <td>{{ $sale->customer?->sale_center }}</td>
                                </tr>

                                <tr>
                                    <td class="fw-bold pe-2">Area Name</td>
                                    <td>:</td>
                                    <td>{{ $sale->customer?->area }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Mobile</td>
                                    <td>:</td>
                                    <td>{{ $sale->customer?->phone }}</td>
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
                                    <td>{{ $sale->customer?->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Payment Type</td>
                                    <td class="pe-2">:</td>
                                    <td>{{ ucfirst($sale->payment_type) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Order No</td>
                                    <td class="pe-2">:</td>
                                    <td>#{{ $sale->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Order Date</td>
                                    <td class="pe-2">:</td>
                                    <td>{{ Carbon\Carbon::parse($sale->order_date)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold pe-2">Delivery Date</td>
                                    <td class="pe-2">:</td>
                                    <td>{{ Carbon\Carbon::parse($sale->delivery_date)->format('d M Y') }}</td>
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
                        <th class="p-1">Doses Form</th>
                        <th class="p-1">Qty</th>
                        <th class="p-1">Bonus</th>
                        <th class="p-1">TP</th>
                        <th class="p-1">Dis%</th>
                        <th class="p-1">Amount</th>
                        <th class="p-1">Bonus Facility</th>
                    </tr>
                </thead>
                <tbody class="p-0">
                    @foreach($sale->items as $key => $item)
                    <tr class="p-0">
                        <td style="padding:0px 2px;">{{ $key + 1 }}</td>
                        <td style="padding:0px 2px;">{{ $item->product->name }} {{ $item->product?->unit?->name }}</td>
                        <td style="padding:0px 2px;">{{ $item->does_form }}</td>
                        <td style="padding:0px 2px;">{{ $item->quantity }}</td>
                        <td style="padding:0px 2px;">{{ $item->bonus ?? 0 }}</td>
                        <td style="padding:0px 2px;">{{ number_format($item->price,2) }}</td>
                        <td style="padding:0px 2px;">{{ $item->discount ?? 0 }}</td>
                        <td style="padding:0px 2px;">{{ number_format($item->sub_total,2) }}</td>
                        <td style="padding:0px 2px;">{{ $item->bonus_facility ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end" style="padding:0px 5px;">
                            Amount<br>
                            Discount<br>
                            Total Amount<br>
                            Paid Amount<br>
                            Due Amount
                        </th>
                        <th  style="padding:0px 5px;">
                            {{ number_format($sale->total,2) }} <br>
                            @if ($sale->discount_type == 'fixed')
                                {{ $sale->discount_amount }}
                            @elseif($sale->discount_type == 'percentage')
                                {{ number_format($sale->discount_value) . '% ('.$sale->discount_amount.')' }}
                            @endif<br>
                            {{ $sale->grand_total }} <br>
                            {{ number_format($paymentTransaction->paid,2) }} <br>
                            {{ number_format($sale->grand_total - $paymentTransaction->paid,2) }}
                        </th>
                        <th  style="padding:0px 5px;"></th>
                    </tr>
                </tfoot>
            </table>

            {{-- AMOUNT IN WORDS --}}
            <p>
                <strong>Taka (In Words):</strong>
                {{ Str::ucfirst($words) }} only
            </p>

            {{-- SIGNATURE --}}
            <div class="row mt-2 text-center">
                <div class="col-4">
                    _____________________ <br>
                    Customer's Signature
                </div>
                <div class="col-4">
                    _____________________ <br>
                    Prepared By
                </div>
                <div class="col-4">
                    _____________________ <br>
                    Authorized Signature
                </div>
            </div>

            {{-- NOTE (shows once, at the end of the document) --}}
            <div class="print-footer-note">
                <strong>বি:দ্র:</strong> ড্যামেজড এবং এক্সপায়ার্ড প্রোডাক্ট ফেরৎযোগ্য নয়।
            </div>
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
