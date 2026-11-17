@extends('backend.layouts.master')
@push('title')
    Stock Print
@endpush
@push('css')
    <style>
        .table-borderless,
        .table-borderless tr,
        .table-borderless td,
        .table-borderless th {
            border: none !important;
            padding: 0px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
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
                    <h6 class="text-uppercase">Stock Report</h6>
                    <p class="mb-0" style="font-size:12px;">Date: {{ now()->format('d M Y') }}</p>
                </div>
            </div>

            {{-- STOCK TABLE --}}
            <table class="table table-bordered mb-2 mt-2" style="border:1px solid #ADADAD">
                <thead class="table-secondary p-0">
                    <tr class="p-0">
                        <th class="p-1">SL</th>
                        <th class="p-1">Product Name</th>
                        <th class="p-1 text-end">Product Price</th>
                        <th class="p-1 text-end">Available Quantity</th>
                        <th class="p-1 text-end">Stock Value</th>
                    </tr>
                </thead>
                <tbody class="p-0">
                    @php $totalStockValue = 0; @endphp
                    @foreach($stocks as $stock)
                        @php
                            $price = $stock->product?->price ?? 0;
                            $value = $price * $stock->quantity;
                            $totalStockValue += $value;
                        @endphp
                        <tr class="p-0">
                            <td style="padding:0px 2px;">{{ $loop->iteration }}</td>
                            <td style="padding:0px 2px;">{{ $stock->product?->name }} {{ $stock->product?->unit?->name }}</td>
                            <td style="padding:0px 2px;" class="text-end">{{ number_format($price, 2) }}</td>
                            <td style="padding:0px 2px;" class="text-end">{{ $stock->quantity }}</td>
                            <td style="padding:0px 2px;" class="text-end">{{ number_format($value, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end" style="padding:0px 5px;">Total Stock Value</th>
                        <th class="text-end" style="padding:0px 5px;">৳ {{ number_format($totalStockValue, 2) }}</th>
                    </tr>
                </tfoot>
            </table>

            {{-- AMOUNT IN WORDS (optional) --}}
            @isset($words)
            <p>
                <strong>Taka (In Words):</strong>
                {{ Str::ucfirst($words) }} only
            </p>
            @endisset

            {{-- SIGNATURE --}}
            <div class="row mt-4 text-center">
                <div class="col-8"></div>
                <div class="col-4">
                    _____________________ <br>
                    Authorized Signature
                </div>
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
