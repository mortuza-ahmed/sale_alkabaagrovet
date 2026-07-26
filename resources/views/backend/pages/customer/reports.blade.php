@extends('backend.layouts.master')
@push('title')
    Customer Payment History
@endpush
@push('css')
    <link href="{{ asset('storage/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('storage/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
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
        <div class="container-fluid mt-5">
            <div class="page-content-wrapper">
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <!-- Card Header -->
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title m-0">Payment History</h4>
                                @if (isset($payments) && count($payments) > 0)
                                    <a href="{{ route('customers.paymentReportsPrint', ['from_date' => $fromDate, 'to_date' => $toDate, 'customer_id' => $customerId]) }}"
                                        class="card-title m-0 btn btn-info btn-sm text-white"><i class="fas fa-print"></i>
                                        Print</a>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">

                                <!-- Date Filter Form -->
                                <div class="row mb-3 d-flex justify-content-center">
                                    <div class="col-12 col-md-12">
                                        <form action="{{ route('customers.paymentReports') }}" method="post"
                                            class="row g-2 d-flex justify-content-center align-items-center">
                                            @csrf
                                            <div class="row m-0 p-0 d-flex justify-content-center">
                                                <div class="col-12 col-sm-6 col-md-2 m-0 p-0">
                                                    <label for="from_date" class="col-form-label">Customer:</label>
                                                    <select name="customer_id" id="customer_id"
                                                        class="form-control form-control-sm" required>
                                                        <option value="">Select Customer</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}"
                                                                @if (isset($payments) && count($payments) > 0 && $customer->id == $customerId) selected @endif>
                                                                {{ $customer->name ?? '' }} ({{ $customer->address ?? '' }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-12 col-sm-6 col-md-2 m-0">
                                                    <label for="from_date" class="col-form-label">From:</label>
                                                    <input type="date" id="from_date" name="from_date"
                                                        class="form-control form-control-sm" value="{{ $fromDate }}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-2 m-0">
                                                    <label for="to_date" class="col-form-label">To:</label>
                                                    <input type="date" id="to_date" name="to_date"
                                                        class="form-control form-control-sm" value="{{ $toDate }}">
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-2 m-0 d-flex align-items-end">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary w-100">Filter</button>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-2 m-0 d-flex align-items-end">
                                                    <a href="{{ route('customers.paymentReports') }}"
                                                        class="btn btn-sm btn-secondary w-100">Reset</a>
                                                </div>
                                            </div>
                                        </form>

                                        @if ($fromDate && $toDate)
                                            <p class="text-muted text-center mt-3">Showing payment history from
                                                <strong>{{ $fromDate }}</strong> to
                                                <strong>{{ $toDate }}</strong>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Summary Cards Section -->

                                @if (isset($payments) && count($payments) > 0)
                                    @php
                                        $cus = $payments->first();
                                    @endphp
                                    <div class="row my-4">
                                        <div class="col-4">
                                            <table class="table table-bordered mb-1" style="border:1px solid #e4e4e4; ">
                                                <tr class="">
                                                    <td class="w-100 p-1">
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
                                                                <tr>
                                                                    <td class="fw-bold pe-2">Total Sales</td>
                                                                    <td>:</td>
                                                                    <td class="fw-bold">৳
                                                                        {{ number_format(paymentHistoryTotal($cus->customer?->id), 2) }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold pe-2">Total Paid</td>
                                                                    <td>:</td>
                                                                    <td class="fw-bold">৳
                                                                        {{ number_format(paymentHistoryPaid($cus->customer?->id), 2) }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold pe-2">Total Due</td>
                                                                    <td>:</td>
                                                                    <td class="fw-bold">৳
                                                                        {{ number_format(paymentHistoryDue($cus->customer?->id), 2) }}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div style="width: 100%; overflow-x: auto;">
                                        <table id="datatable" class="table table-sm table-striped table-bordered"
                                            style="border-collapse: collapse; border-spacing: 0;">
                                            <thead>
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Payment Date</th>
                                                    <th>Paid</th>
                                                    <th>Payment Status</th>
                                                    <th>Note</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $grandTotal = 0;
                                                @endphp
                                                @foreach ($payments as $payment)
                                                    @php
                                                        $grandTotal = $grandTotal + $payment->paid;
                                                    @endphp
                                                    @if ($payment->paid > 0)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ Carbon\Carbon::parse($payment->payment_date)->format('d M Y') ?? '' }}</td>
                                                            <td>{{ number_format($payment->paid, 2) ?? '' }}</td>
                                                            <td>{{ $payment->payment_status ?? '' }}</td>
                                                            <td>{{ $payment->note ?? 'Cash' }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-end">Grand Total:</th>
                                                    <th>{{ number_format($grandTotal, 2) }}</th>
                                                    <th colspan="2"></th>
                                                </tr>
                                        </tfoot>
                                        </table>
                                    </div>
                                @endif

                            </div> <!-- card-body -->

                        </div> <!-- card -->
                    </div> <!-- col-12 -->
                </div> <!-- row -->
            </div> <!-- page-content-wrapper -->
        </div> <!-- container-fluid -->
    </div> <!-- page-content -->
@endsection

@push('scripts')
    <script src="{{ asset('storage/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('storage/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('storage/assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Initialize DataTable
            $('#datatable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100]
            });

            // Initialize Select2 for customer
            $('#customer_id').select2({
                placeholder: "Select a customer",
                allowClear: true
            });
        });
    </script>
@endpush
