@extends('backend.layouts.master')
@push('title')
    Customer Sales & Collection Reports
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
                                <h4 class="card-title m-0">All Customer Sales & Collections</h4>
                                @if (isset($customers) && count($customers) > 0)
                                    <a href="{{ route('customers.salesCollectionsReportsPrint', ['from_date' => $fromDate, 'to_date' => $toDate]) }}"
                                        target="_blank" class="btn btn-info btn-sm text-white">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                @endif
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <!-- Date Filter Form -->
                                <div class="row mb-3 d-flex justify-content-center">
                                    <div class="col-12 col-md-12">
                                        <form action="{{ route('customers.salesCollectionsreports') }}" method="post"
                                            class="row g-2 d-flex justify-content-center align-items-center">
                                            @csrf
                                            <div class="row m-0 p-0 d-flex justify-content-center">
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
                                                    <a href="{{ route('customers.salesCollectionsreports') }}"
                                                        class="btn btn-sm btn-secondary w-100">Reset</a>
                                                </div>
                                            </div>
                                        </form>

                                        @if ($fromDate && $toDate)
                                            <p class="text-muted text-center mt-3">Sales and Collections Report from
                                                <strong>{{ $fromDate }}</strong> to
                                                <strong>{{ $toDate }}</strong>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div> <!-- card-body -->

                        </div> <!-- card -->

                        @if (isset($customers) && count($customers) > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div style="width: 100%; overflow-x: auto;">
                                                        <table id="datatable" class="table table-sm table-striped table-bordered"
                                                        style="border-collapse: collapse; border-spacing: 0; ">
                                                            <thead>
                                                                <tr>
                                                                    <th>SL</th>
                                                                    <th>Name</th>
                                                                    <th>Phone</th>
                                                                    <th>Address</th>
                                                                    <th>Sale Center</th>
                                                                    <th>District</th>
                                                                    <th>Thana</th>
                                                                    <th>Area</th>
                                                                    <th>Total Sales</th>
                                                                    <th>Total Discount</th>
                                                                    <th>Grand Total</th>
                                                                    <th>Total Collection</th>
                                                                    <th>Total Due</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php
                                                                    $grandTotal = 0;
                                                                    $grandDiscount = 0;
                                                                    $grandGrandTotal = 0;
                                                                    $grandCollection = 0;
                                                                    $grandDue = 0;
                                                                @endphp
                                                                @foreach($customers as $customer)
                                                                @php
                                                                    $total = customerTotalSale($customer->id,$fromDate,$toDate);
                                                                    $totalDiscount = customerTotalSaleDiscounted($customer->id,$fromDate,$toDate);
                                                                    $totalGrandTotal = customerGrandTotalSale($customer->id,$fromDate,$toDate);
                                                                    $totalCollection = customerTotalCollection($customer->id,$fromDate,$toDate);
                                                                    $totalDue = customerTotalDue($customer->id,$fromDate,$toDate);

                                                                    $grandTotal = $grandTotal + $total;
                                                                    $grandDiscount = $grandDiscount + $totalDiscount;
                                                                    $grandGrandTotal = $grandGrandTotal + $totalGrandTotal;
                                                                    $grandCollection = $grandCollection + $totalCollection;
                                                                    $grandDue = $grandDue + $totalDue;
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $customer?->name }}</td>
                                                                    <td>{{ $customer?->phone }}</td>
                                                                    <td>{{ $customer?->address }}</td>
                                                                    <td>{{ $customer?->sale_center }}</td>
                                                                    <td>{{ $customer?->district }}</td>
                                                                    <td>{{ $customer?->thana }}</td>
                                                                    <td>{{ $customer?->area }}</td>
                                                                    <td>{{ number_format($total) }}</td>
                                                                    <td>{{ number_format($totalDiscount) }}</td>
                                                                    <td>{{ number_format($totalGrandTotal) }}</td>
                                                                    <td>{{ number_format($totalCollection) }}</td>
                                                                    <td>{{ number_format($totalDue) }}</td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="8" class="text-end">Grand Total:</th>
                                                                    <th>{{ number_format($grandTotal) }}</th>
                                                                    <th>{{ number_format($grandDiscount) }}</th>
                                                                    <th>{{ number_format($grandGrandTotal) }}</th>
                                                                    <th>{{ number_format($grandCollection) }}</th>
                                                                    <th>{{ number_format($grandDue) }}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


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

        });
    </script>
@endpush
