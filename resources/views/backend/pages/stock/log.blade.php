@extends('backend.layouts.master')
@push('title')
    Stock Log
@endpush
@push('css')
    <link href="{{ asset('storage/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

@endpush
@section('content')
       <div class="page-content">
                    <div class="container-fluid mt-5">
                        <div class="page-content-wrapper">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h4 class="card-title m-0">Stock Log</h4>
                                        </div>
                                        <div class="card-body">
                                            <div style="width: 100%; overflow-x: auto;">
                                                <table id="datatable" class="table table-sm table-striped table-bordered"
                                                style="border-collapse: collapse; border-spacing: 0; ">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Supplier</th>
                                                            <th>Customer</th>
                                                            <th>Purchase</th>
                                                            <th>Sale</th>
                                                            <th>Product</th>
                                                            <th>Quantity</th>
                                                            <th>Bonus</th>
                                                            <th>Bonus Facility</th>
                                                            <th>Date</th>
                                                            <th class="text-center">Stock Type</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($stockLogs as $stockLog)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $stockLog->supplier?->name }}</td>
                                                            <td>{{ $stockLog->customer?->name }}</td>
                                                            <td>
                                                                @if($stockLog->purchase_id)
                                                                    <a href="{{ route('purchases.show', ['purchase' => $stockLog->purchase_id]) }}">
                                                                        #{{ $stockLog->purchase_id }}
                                                                    </a>
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if($stockLog->sale_id)
                                                                    <a href="{{ route('sales.show', ['sale' => $stockLog->sale_id]) }}">
                                                                        #{{ $stockLog->sale_id }}
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>{{ $stockLog->product?->name }} {{ $stockLog->product?->unit?->name }}</td>
                                                            <td>{{ $stockLog->quantity }}</td>
                                                            <td>{{ $stockLog->bonus }}</td>
                                                            <td>{{ $stockLog->bonus_facility }}</td>
                                                            <td>{{ Carbon\Carbon::parse($stockLog->date)->format('d M Y') }}</td>
                                                            <td class="text-center">
                                                                <span class="badge {{ $stockLog->type === 'in' ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ ucfirst($stockLog->type) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
    <script src="{{ asset('storage/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('storage/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#datatable').DataTable({
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100]
            });
        });
    </script>

@endpush
