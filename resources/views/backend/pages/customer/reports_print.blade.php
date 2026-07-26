@extends('backend.layouts.master')
@push('title')
    Sales Payment History
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
                            <img src="{{ asset(auth()->user()->company_logo) }}" alt="Company Logo" style="max-height: 100px;">
                        @endif
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-success">{{ auth()->user()->company_name ?? 'Company Name Here' }}</h3>
                        <p class="mb-0">{!! auth()->user()->company_address ?? 'Company Address Here' !!}</p>
                        <p class="mb-0">{{ auth()->user()->company_phone ?? 'Company Phone Here' }}</p>
                        <h6 class="mb-0 text-uppercase">Sales Payment History</h6>
                        <p class="mb-0">{{ Carbon\Carbon::parse($fromDate)->format('d M Y') ?? '' }} - {{ Carbon\Carbon::parse($toDate)->format('d M Y') ?? '' }}</p>
                    </div>
                </div>
                {{-- CUSTOMER INFO --}}
                @php
                    $cus = $payments->first();
                @endphp
                @if ($cus)
                    <table class="table table-bordered mb-1" style="border:1px solid #ADADAD;">
                        <tr class="">
                            <td class="w-50 p-1">
                                <div class="d-flex justify-content-between">

                                    <!-- LEFT SIDE -->
                                    <table class="table table-borderless table-sm w-auto mb-0">
                                        <tr>
                                            <td class="fw-bold pe-2">Customer Name & ID</td>
                                            <td class="pe-2">:</td>
                                            <td class="fw-bold">{{ $cus->customer?->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold pe-2">Customer Address</td>
                                            <td>:</td>
                                            <td class="fw-bold">{{ $cus->customer?->address }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold pe-2">Mobile</td>
                                            <td>:</td>
                                            <td class="fw-bold">{{ $cus->customer?->phone }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td class="w-50 p-1">
                                <!-- RIGHT SIDE -->
                                <div class="d-flex justify-content-start">
                                    <table class="table table-borderless table-sm w-auto mb-0">
                                        <tr>
                                            <td class="fw-bold pe-2">Total Sales</td>
                                            <td>:</td>
                                            <td class="fw-bold">
                                                ৳{{ number_format(paymentHistoryTotal($cus->customer?->id, $fromDate, $toDate), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold pe-2">Total Paid</td>
                                            <td>:</td>
                                            <td class="fw-bold">
                                                ৳{{ number_format(paymentHistoryPaid($cus->customer?->id, $fromDate, $toDate), 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold pe-2">Total Due</td>
                                            <td>:</td>
                                            <td class="fw-bold">
                                                ৳{{ number_format(paymentHistoryDue($cus->customer?->id, $fromDate, $toDate), 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>

                    {{-- ITEMS TABLE --}}
                    <table class="table table-bordered my-2" style="border:1px solid #ADADAD">
                        <thead class="table-secondary p-0">
                            <tr class="p-0">
                                <th class="p-1">SL</th>
                                <th class="p-1">Payment Id</th>
                                <th class="p-1">Payment Date</th>
                                <th class="p-1">Paid</th>
                                <th class="p-1">Payment Status</th>
                                <th class="p-1">Note</th>
                            </tr>
                        </thead>
                        <tbody class="p-0">
                            @php
                                $grandTotal = 0;
                            @endphp
                            @foreach ($payments as $payment)
                                @php
                                    $grandTotal = $grandTotal + $payment->paid;
                                @endphp
                                @if ($payment->paid > 0)
                                    <tr class="p-0">
                                        <td style="padding:0px 2px;">{{ $loop->iteration }}</td>
                                        <td style="padding:0px 2px;">#{{ $payment->id }}</td>
                                        <td style="padding:0px 2px;">{{ Carbon\Carbon::parse($payment->payment_date)->format('d M Y') ?? '' }}</td>
                                        <td style="padding:0px 2px;">৳{{ number_format($payment->paid, 2) ?? '' }}</td>
                                        <td style="padding:0px 2px;">{{ $payment->payment_status ?? '' }}</td>
                                        <td style="padding:0px 2px;">{{ $payment->note ?? 'Cash' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="p-0">
                                <th colspan="3" class="text-end">Total:</th>
                                <th style="padding:0px 2px;">৳{{ number_format($grandTotal, 2) }}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- AMOUNT IN WORDS --}}
                    @php
                        function numberToWordsBD($amount)
                        {
                            $ones = [
                                0 => '',
                                1 => 'one',
                                2 => 'two',
                                3 => 'three',
                                4 => 'four',
                                5 => 'five',
                                6 => 'six',
                                7 => 'seven',
                                8 => 'eight',
                                9 => 'nine',
                                10 => 'ten',
                                11 => 'eleven',
                                12 => 'twelve',
                                13 => 'thirteen',
                                14 => 'fourteen',
                                15 => 'fifteen',
                                16 => 'sixteen',
                                17 => 'seventeen',
                                18 => 'eighteen',
                                19 => 'nineteen',
                            ];

                            $tens = [
                                2 => 'twenty',
                                3 => 'thirty',
                                4 => 'forty',
                                5 => 'fifty',
                                6 => 'sixty',
                                7 => 'seventy',
                                8 => 'eighty',
                                9 => 'ninety',
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
                    @endphp
                    <p>
                        <strong>Total Sales Taka (In Words):</strong>
                        {{ Str::ucfirst(numberToWordsBD(paymentHistoryTotal($cus->customer?->id))) }} taka only <br>
                        <strong>Total Paid Taka (In Words):</strong>
                        {{ Str::ucfirst(numberToWordsBD(paymentHistoryPaid($cus->customer?->id))) }} taka only <br>
                        <strong>Total Due Taka (In Words):</strong>
                        {{ Str::ucfirst(numberToWordsBD(paymentHistoryDue($cus->customer?->id))) }} taka only
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
