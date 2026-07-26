@extends('backend.layouts.master')
@push('title')
    Purchase Lists
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
                                            <h4 class="card-title m-0">Purchase Lists</h4>
                                            <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-success waves-effect waves-light"><i class="fas fa-plus"></i> Add New</a>
                                        </div>
                                        <div class="card-body">
                                            <div style="width: 100%; overflow-x: auto;">
                                                <table id="datatable" class="table table-sm table-striped table-bordered"
                                                style="border-collapse: collapse; border-spacing: 0; ">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Purchase ID</th>
                                                            <th>Supplier</th>
                                                            <th>Purchase Date</th>
                                                            <th>Total Amount</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($purchases as $purchase)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td> <a href="{{ route('purchases.show', $purchase->id) }}"># {{ $purchase->id }}</a></td>
                                                            <td>{{ $purchase->supplier?->name }}</td>
                                                            <td>{{ Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') ?? '' }}</td>
                                                            <td>{{ number_format($purchase->total_amount) ?? '' }}</td>
                                                            <td class="d-flex justify-content-center align-items-center gap-2">
                                                                <a href="{{ route('purchases.print', $purchase->id) }}" class="btn btn-sm btn-secondary waves-effect waves-light d-flex justify-content-center align-items-center gap-1"><i class="fas fa-print"></i> Print</a>
                                                                <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-info waves-effect waves-light d-flex justify-content-center align-items-center gap-1"><i class="fas fa-eye"></i> View</a>
                                                                <a href="{{ route('purchases.edit', $purchase->id) }}" class="btn btn-sm btn-primary waves-effect waves-light d-flex justify-content-center align-items-center gap-1"><i class="fas fa-edit"></i> Edit</a>
                                                                <form action="{{ route('purchases.destroy', $purchase->id) }}"
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
