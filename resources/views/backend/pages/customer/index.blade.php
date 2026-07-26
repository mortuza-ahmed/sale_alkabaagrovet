@extends('backend.layouts.master')
@push('title')
    Customer Lists
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
                                            <h4 class="card-title m-0">Customer Lists</h4>
                                            <a href="{{ route('customers.create') }}" class="btn btn-sm btn-success waves-effect waves-light"><i class="fas fa-plus"></i> Add New</a>
                                        </div>
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
                                                            <th>Created</th>
                                                            <th>Total Sales</th>
                                                            <th>Paid</th>
                                                            <th>Due</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $grandTotal = 0;
                                                            $grandPaid  = 0;
                                                            $grandDue   = 0;
                                                        @endphp
                                                        @foreach($customers as $customer)
                                                        @php
                                                            $total = paymentHistoryTotal($customer->id);
                                                            $paid  = paymentHistoryPaid($customer->id);
                                                            $due   = paymentHistoryDue($customer->id);

                                                            $grandTotal = $grandTotal + $total;
                                                            $grandPaid  = $grandPaid + $paid;
                                                            $grandDue   = $grandDue + $due;
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
                                                            <td>{{ $customer?->created_at->format('d M Y') }}</td>
                                                            <td>{{ number_format($total) }}</td>
                                                            <td>{{ number_format($paid) }}</td>
                                                            <td>{{ number_format($due) }}</td>
                                                            <td class="d-flex justify-content-center align-items-center gap-2">
                                                                <a href="{{ route('customers.payment',$customer->id) }}" class="btn btn-sm btn-info waves-effect waves-light d-flex justify-content-center align-items-center gap-1"><i class="fas fa-money-check-alt"></i>Sales Lists</a>
                                                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-primary waves-effect waves-light d-flex justify-content-center align-items-center gap-1"><i class="fas fa-edit"></i> Edit</a>
                                                                <form action="{{ route('customers.destroy', $customer->id) }}"
                                                                    method="POST"
                                                                    style="display:inline-block"
                                                                    onsubmit="return confirm('Are you sure?')">
                                                                    @csrf
                                                                    @method('DELETE')

                                                                    <button type="submit" class="btn btn-sm btn-danger d-flex justify-content-center align-items-center gap-1">
                                                                        <i class="fas fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr class="p-0">
                                                        <th colspan="9" class="text-end">Total:</th>
                                                        <th style="padding:0px 2px;">৳{{ number_format($grandTotal) }}</th>
                                                        <th style="padding:0px 2px;">৳{{ number_format($grandPaid) }}</th>
                                                        <th style="padding:0px 2px;">৳{{ number_format($grandDue) }}</th>
                                                        <th colspan="1"></th>
                                                    </tr>
                                                </tfoot>
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
