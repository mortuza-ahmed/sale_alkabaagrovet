@extends('backend.layouts.master')
@push('title')
    Sale Lists
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
                                            <h4 class="card-title m-0">Sale Lists</h4>
                                            <a href="{{ route('sales.create') }}" class="btn btn-sm btn-success waves-effect waves-light"><i class="fas fa-plus"></i> Add New</a>
                                        </div>
                                        <div class="card-body">
                                            <div style="width: 100%; overflow-x: auto;">
                                                <table id="datatable" class="table table-sm table-striped table-bordered"
                                                style="border-collapse: collapse; border-spacing: 0; ">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Sale ID</th>
                                                            <th>Customer</th>
                                                            <th>Payment Type</th>
                                                            <th>Order Date</th>
                                                            <th>Delivery Date</th>
                                                            <th>Sale Date</th>
                                                            <th>Amount</th>
                                                            <th>Discount</th>
                                                            <th>Total Amount</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($sales as $sale)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td> <a href="{{ route('sales.show', $sale->id) }}"># {{ $sale->id }}</a></td>
                                                            <td>{{ $sale->customer?->name }}</td>
                                                            <td>{{ $sale->payment_type ?? 'Cash' }}</td>
                                                            <td>{{ Carbon\Carbon::parse($sale->order_date)->format('d M Y') ?? '' }}</td>
                                                            <td>{{ Carbon\Carbon::parse($sale->delivery_date)->format('d M Y') ?? '' }}</td>
                                                            <td>{{ Carbon\Carbon::parse($sale->date)->format('d M Y') ?? '' }}</td>
                                                            <td class="text-start">{{ $sale->total ?? '' }}</td>
                                                            <td class="text-start">
                                                                @if($sale->discount_type == 'fixed')
                                                                    {{ number_format($sale->discount_amount, 2) }}
                                                                @elseif($sale->discount_type == 'percentage')
                                                                    {{ rtrim(rtrim($sale->discount_value, '0'), '.') }}% ({{ number_format($sale->discount_amount, 2) }})
                                                                @endif
                                                            </td>
                                                            <td class="text-start">{{ number_format($sale->grand_total) ?? '' }}</td>
                                                            <td class="d-flex justify-content-center align-items-center gap-2">
                                                                <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-sm btn-secondary waves-effect waves-light d-flex justify-content-center align-items-center gap-1"><i class="fas fa-print"></i> Print</a>
                                                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info waves-effect waves-light d-flex justify-content-center align-items-center gap-1"><i class="fas fa-eye"></i> View</a>
                                                                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-primary waves-effect waves-light d-flex justify-content-center align-items-center gap-1"><i class="fas fa-edit"></i> Edit</a>
                                                                <form action="{{ route('sales.destroy', $sale->id) }}"
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
                                                        <tr>
                                                            <th colspan="7" class="text-end">Grand Total:</th>
                                                            <th class="text-start">{{ number_format($sales->sum('total')) }}</th>
                                                            <th class="text-start">{{ number_format($sales->sum('discount_amount')) }}</th>
                                                            <th class="text-start">{{ number_format($sales->sum('grand_total')) }}</th>
                                                            <th></th>
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
