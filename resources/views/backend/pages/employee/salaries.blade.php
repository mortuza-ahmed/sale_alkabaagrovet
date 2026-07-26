@extends('backend.layouts.master')
@push('title')
    Salaries
@endpush
@push('css')
<link href="{{ asset('storage/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Employee Form -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{ route('salaries.store') }}" method="POST" id="employeeForm">
                    @csrf
                    <input type="hidden" name="employee_id" id="employee_id">
                    <div class="row">
                        <div class="col-md-8 mb-2">
                            <label for="salary_month" class="form-label">Salary Month</label>
                            <input type="month" class="form-control form-control-sm" id="salary_month" name="salary_month" placeholder="Enter salary month" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end mb-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100" id="submitBtn">
                                <i class="fas fa-save"></i> Salary Open
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="card">
            <div class="card-body">
                <div style="width: 100%; overflow-x: auto;">
                    <table id="datatable" class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Salary Month</th>
                                <th>Total Employee</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaries as $salary)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $salary->month_year)->format('F Y') }}</td>
                                <td>{{ $salary->total_employee }}</td>
                                <td class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('salaries.show',$salary->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Salaries View</a>
                                    <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
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
@endsection

@push('scripts')
<script src="{{ asset('storage/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function () {

    let table = $('#datatable').DataTable({
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
    });

});
</script>
@endpush
