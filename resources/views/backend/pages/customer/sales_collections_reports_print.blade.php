@extends('backend.layouts.master')
@push('title')
    Customer Sales & Collection Reports Print
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

        /* প্রিন্ট করার সময় সাইডবার/ন্যাভবার হাইড করে শুধু ইনভয়েস প্রিন্ট হবে */
        @media print {
            body * {
                visibility: hidden;
            }
            #invoice, #invoice * {
                visibility: visible;
            }
            #invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            @page {
                size: A4 landscape; /* বেশি কলাম থাকায় ল্যান্ডস্কেপ পারফেক্ট হবে */
                margin: 8mm;
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
                        @if (auth()->user()->company_logo)
                            <img src="{{ asset(auth()->user()->company_logo) }}" alt="Company Logo" style="max-height: 80px;" class="me-3">
                        @endif
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-success mb-1">{{ auth()->user()->company_name ?? 'Company Name Here' }}</h3>
                        <p class="mb-0">{!! auth()->user()->company_address ?? 'Company Address Here' !!}</p>
                        <p class="mb-0">{{ auth()->user()->company_phone ?? 'Company Phone Here' }}</p>
                        <h6 class="mb-0 text-uppercase fw-bold mt-1">Customer Sales & Collection Report</h6>
                        @if ($fromDate && $toDate)
                            <p class="mb-0 text-muted">{{ Carbon\Carbon::parse($fromDate)->format('d M Y') }} - {{ Carbon\Carbon::parse($toDate)->format('d M Y') }}</p>
                        @else
                            <p class="mb-0 text-muted">All-Time Report</p>
                        @endif
                    </div>
                </div>

                {{-- SALES & COLLECTION TABLE --}}
                @if (isset($customers) && count($customers) > 0)
                    <table class="table table-bordered my-2" style="border:1px solid #ADADAD; font-size: 11px;">
                        <thead class="table-secondary p-0">
                            <tr class="p-0 text-center">
                                <th class="p-1">SL</th>
                                <th class="p-1">Name</th>
                                <th class="p-1">Phone</th>
                                <th class="p-1">Address</th>
                                <th class="p-1">Sale Center</th>
                                <th class="p-1">District</th>
                                <th class="p-1">Thana</th>
                                <th class="p-1">Area</th>
                                <th class="p-1">Total Sales</th>
                                <th class="p-1">Total Discount</th>
                                <th class="p-1">Grand Total</th>
                                <th class="p-1">Total Collection</th>
                                <th class="p-1">Total Due</th>
                            </tr>
                        </thead>
                        <tbody class="p-0">
                            @php
                                $grandTotal = 0;
                                $grandDiscount = 0;
                                $grandGrandTotal = 0;
                                $grandCollection = 0;
                                $grandDue = 0;
                            @endphp
                            @foreach ($customers as $customer)
                                @php
                                    $total = customerTotalSale($customer->id, $fromDate, $toDate);
                                    $totalDiscount = customerTotalSaleDiscounted($customer->id, $fromDate, $toDate);
                                    $totalGrandTotal = customerGrandTotalSale($customer->id, $fromDate, $toDate);
                                    $totalCollection = customerTotalCollection($customer->id, $fromDate, $toDate);
                                    $totalDue = customerTotalDue($customer->id, $fromDate, $toDate);

                                    $grandTotal += $total;
                                    $grandDiscount += $totalDiscount;
                                    $grandGrandTotal += $totalGrandTotal;
                                    $grandCollection += $totalCollection;
                                    $grandDue += $totalDue;
                                @endphp
                                <tr>
                                    <td class="text-center" style="padding: 1px 3px;">{{ $loop->iteration }}</td>
                                    <td style="padding: 1px 3px;">{{ $customer?->name }}</td>
                                    <td style="padding: 1px 3px;">{{ $customer?->phone }}</td>
                                    <td style="padding: 1px 3px;">{{ $customer?->address }}</td>
                                    <td style="padding: 1px 3px;">{{ $customer?->sale_center }}</td>
                                    <td style="padding: 1px 3px;">{{ $customer?->district }}</td>
                                    <td style="padding: 1px 3px;">{{ $customer?->thana }}</td>
                                    <td style="padding: 1px 3px;">{{ $customer?->area }}</td>
                                    <td class="text-end" style="padding: 1px 3px;">{{ number_format($total) }}</td>
                                    <td class="text-end" style="padding: 1px 3px;">{{ number_format($totalDiscount) }}</td>
                                    <td class="text-end" style="padding: 1px 3px;">{{ number_format($totalGrandTotal) }}</td>
                                    <td class="text-end" style="padding: 1px 3px;">{{ number_format($totalCollection) }}</td>
                                    <td class="text-end" style="padding: 1px 3px;">{{ number_format($totalDue) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="p-0 fw-bold">
                                <th style="padding: 2px;" colspan="8" class="text-end">Grand Total:</th>
                                <th style="padding: 2px;" class="text-end">{{ number_format($grandTotal) }}</th>
                                <th style="padding: 2px;" class="text-end">{{ number_format($grandDiscount) }}</th>
                                <th style="padding: 2px;" class="text-end">{{ number_format($grandGrandTotal) }}</th>
                                <th style="padding: 2px;" class="text-end">{{ number_format($grandCollection) }}</th>
                                <th style="padding: 2px;" class="text-end">{{ number_format($grandDue) }}</th>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- AMOUNT IN WORDS --}}
                    @php
                        if (!function_exists('numberToWordsBD')) {
                            function numberToWordsBD($amount)
                            {
                                $ones = [
                                    0 => '', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
                                    5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
                                    10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
                                    14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
                                    18 => 'eighteen', 19 => 'nineteen'
                                ];

                                $tens = [
                                    2 => 'twenty', 3 => 'thirty', 4 => 'forty', 5 => 'fifty',
                                    6 => 'sixty', 7 => 'seventy', 8 => 'eighty', 9 => 'ninety'
                                ];

                                $num = floor($amount);
                                $paisa = round(($amount - $num) * 100);

                                $convert = function ($num) use (&$convert, $ones, $tens) {
                                    $str = '';
                                    if ($num >= 10000000) {
                                        $str .= $convert(intval($num / 10000000)) . ' crore ';
                                        $num %= 10000000;
                                    }
                                    if ($num >= 100000) {
                                        $str .= $convert(intval($num / 100000)) . ' lakh ';
                                        $num %= 100000;
                                    }
                                    if ($num >= 1000) {
                                        $str .= $convert(intval($num / 1000)) . ' thousand ';
                                        $num %= 1000;
                                    }
                                    if ($num >= 100) {
                                        $str .= $convert(intval($num / 100)) . ' hundred ';
                                        $num %= 100;
                                    }
                                    if ($num > 0) {
                                        if ($num < 20) {
                                            $str .= $ones[$num];
                                        } else {
                                            $str .= $tens[intval($num / 10)];
                                            if ($num % 10) {
                                                $str .= ' ' . $ones[$num % 10];
                                            }
                                        }
                                    }
                                    return trim($str);
                                };
                                $words = $convert($num);
                                if ($paisa > 0) {
                                    $words .= ' and ' . $convert($paisa) . ' paisa';
                                }
                                return trim($words);
                            }
                        }
                    @endphp

                    <div class="mt-3" style="font-size: 12px;">
                        <p class="mb-1">
                            <strong>Grand Total (In Words):</strong>
                            {{ Str::ucfirst(numberToWordsBD($grandTotal)) }} taka only
                        </p>
                        <p class="mb-1">
                            <strong>Total Collection (In Words):</strong>
                            {{ Str::ucfirst(numberToWordsBD($grandCollection)) }} taka only
                        </p>
                        <p class="mb-1">
                            <strong>Total Due (In Words):</strong>
                            {{ Str::ucfirst(numberToWordsBD($grandDue)) }} taka only
                        </p>
                    </div>

                    {{-- SIGNATURE --}}
                    <div class="row mt-5 text-center" style="font-size: 12px;">
                        <div class="col-4">
                            _____________________ <br>
                            Prepared By
                        </div>
                        <div class="col-4">
                            _____________________ <br>
                            Verified By
                        </div>
                        <div class="col-4">
                            _____________________ <br>
                            Authorized Signature
                        </div>
                    </div>
                @endif
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
