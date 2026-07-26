@extends('backend.layouts.master')
@push('title')
    Stock Lists
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
                                            <h4 class="card-title m-0">Stock Lists</h4>
                                        </div>
                                        <div class="card-body">
                                            <div style="width: 100%; overflow-x: auto;">
                                                <table id="datatable" class="table table-sm table-striped table-bordered"
                                                style="border-collapse: collapse; border-spacing: 0; ">
                                                    <thead>
                                                        <tr>
                                                            <th>SL</th>
                                                            <th>Product Name</th>
                                                            <th>Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($stocks as $stock)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $stock->product?->name }} {{ $stock->product?->unit?->name }}</td>
                                                            <td>{{ $stock->quantity }}</td>
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
