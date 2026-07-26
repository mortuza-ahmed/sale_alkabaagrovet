@extends('backend.layouts.master')
@push('title')
    Employee Lists
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
                <form action="{{ route('employees.store') }}" method="POST" id="employeeForm">
                    @csrf
                    <input type="hidden" name="employee_id" id="employee_id">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control form-control-sm" id="name" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="number" class="form-control form-control-sm" id="phone" name="phone" placeholder="Enter phone" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control form-control-sm" id="address" name="address" placeholder="Enter address" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="joining_date" class="form-label">Joingin Date</label>
                            <input type="date" class="form-control form-control-sm" id="joining_date" name="joining_date" required>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="basic_salary" class="form-label">Basic Salary</label>
                            <input type="number" class="form-control form-control-sm" id="basic_salary" placeholder="Enter basic salary" name="basic_salary" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="bill_allowance" class="form-label">TA/DA/Others</label>
                            <input type="number" class="form-control form-control-sm" id="bill_allowance" placeholder="Enter TA/DA/Others bill" name="bill_allowance" required>
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

        <!-- Employee Table -->
        <div class="card">
            <div class="card-body">
                <div style="width: 100%; overflow-x: auto;">
                    <table id="datatable" class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Joining Date</th>
                                <th>Basic Salary</th>
                                <th>TA/DA/Others Bill</th>
                                <th>Total Salary</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td>{{ $employee->address }}</td>
                                <td>{{ $employee->joining_date }}</td>
                                <td>{{ number_format($employee->basic_salary,2) }}</td>
                                <td>{{ number_format($employee->bill_allowance,2) }}</td>
                                <td>{{ number_format($employee->total_salary,2) }}</td>
                                <td class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary editEmployeeBtn"
                                        data-id="{{ $employee->id }}"
                                        data-name="{{ $employee->name }}"
                                        data-phone="{{ $employee->phone }}"
                                        data-address="{{ $employee->address }}"
                                        data-joining_date="{{ $employee->joining_date }}"
                                        data-basic_salary="{{ $employee->basic_salary }}"
                                        data-bill_allowance="{{ $employee->bill_allowance }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display:inline-block">
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

    // Inline edit
    $('.editEmployeeBtn').on('click', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let phone = $(this).data('phone');
        let address = $(this).data('address');
        let joining_date = $(this).data('joining_date');
        let basic_salary = $(this).data('basic_salary');
        let bill_allowance = $(this).data('bill_allowance');

        $('#employee_id').val(id);
        $('#name').val(name);
        $('#phone').val(phone);
        $('#address').val(address);
        $('#joining_date').val(joining_date);
        $('#basic_salary').val(basic_salary);
        $('#bill_allowance').val(bill_allowance);

        $('#submitBtn').html('<i class="fas fa-save"></i> Update');
    });

});
</script>
@endpush
