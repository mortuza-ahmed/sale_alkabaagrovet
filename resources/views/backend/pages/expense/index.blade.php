@extends('backend.layouts.master')
@push('title')
    Expense Lists
@endpush
@push('css')
<link href="{{ asset('storage/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Expense Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST" id="expenseForm">
                    @csrf
                    <input type="hidden" name="expense_id" id="expense_id">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="reason" class="form-label">Reason</label>
                            <input type="text" class="form-control form-control-sm" id="reason" name="reason" placeholder="Enter reason" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="cost" class="form-label">Cost</label>
                            <input type="number" class="form-control form-control-sm" id="cost" name="cost" min="1" placeholder="Enter cost" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control form-control-sm" id="date" name="date" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end mb-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100" id="submitBtn">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Date Filter -->
    <div class="card mb-2">
        <div class="card-body">
            <div class="row g-2 justify-content-center text-center">
                <div class="col-md-3">
                    <label for="from_date">From Date</label>
                    <input type="date" class="form-control form-control-sm" id="from_date">
                </div>
                <div class="col-md-3">
                    <label for="to_date">To Date</label>
                    <input type="date" class="form-control form-control-sm" id="to_date">
                </div>
                <div class="col-md-3 d-flex justify-content-center align-items-end gap-2">
                    <button class="btn btn-sm btn-primary w-100" id="filterBtn">Filter</button>
                    <button class="btn btn-sm btn-secondary w-100" id="resetBtn">Reset</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Expense Table -->
        <div class="card">
            <div class="card-body">
                <div style="width: 100%; overflow-x: auto;">
                    <table id="datatable" class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Reason</th>
                                <th>Cost</th>
                                <th>Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expenses as $expense)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $expense->reason }}</td>
                                <td>{{ $expense->cost }}</td>
                                <td>{{ $expense->date }}</td>
                                <td class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary editExpenseBtn"
                                        data-id="{{ $expense->id }}"
                                        data-reason="{{ $expense->reason }}"
                                        data-cost="{{ $expense->cost }}"
                                        data-date="{{ $expense->date }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Grand Total:</th>
                                <th id="grandTotal">{{ $expenses->sum('cost') }}</th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
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

    let table = $('#datatable').DataTable({
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        footerCallback: function ( row, data, start, end, display ) {
            // Update Grand Total dynamically
            let api = this.api();
            let total = api.column(2, { page: 'current'} ).data().reduce(function(a,b){ return parseFloat(a)+parseFloat(b); }, 0);
            $('#grandTotal').html(total.toFixed(2));
        }
    });

    // Inline edit
    $('.editExpenseBtn').on('click', function() {
        let id = $(this).data('id');
        let reason = $(this).data('reason');
        let cost = $(this).data('cost');
        let date = $(this).data('date');

        $('#expense_id').val(id);
        $('#reason').val(reason);
        $('#cost').val(cost);
        $('#date').val(date);

        $('#submitBtn').html('<i class="fas fa-save"></i> Update');
    });

    // Custom date filter
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            let from = $('#from_date').val();
            let to = $('#to_date').val();
            let date = data[3]; // date column index

            if(!from && !to) return true;

            let d = new Date(date);
            if(from && d < new Date(from)) return false;
            if(to && d > new Date(to)) return false;

            return true;
        }
    );

    // Filter button
    $('#filterBtn').on('click', function() { table.draw(); });

    // Reset button
    $('#resetBtn').on('click', function() {
        $('#from_date').val('');
        $('#to_date').val('');
        table.draw();
    });

});
</script>
@endpush
