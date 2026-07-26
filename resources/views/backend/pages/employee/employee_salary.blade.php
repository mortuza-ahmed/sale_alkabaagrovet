@extends('backend.layouts.master')
@push('title')
    Employee Salary
@endpush
@push('css')
<link href="{{ asset('storage/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Employee Table -->
        <div class="card">
            <div class="card-body">
                <div style="width: 100%; overflow-x: auto;">
                    <table id="datatable" class="table table-sm table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Month</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Basic Salary</th>
                                <th>TA/DA/Others Bill</th>
                                <th>Total Salary</th>
                                <th>Total Paid</th>
                                <th>Total Due</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        @php
                            $totalBasic = 0;
                            $totalTaDaOther = 0;
                            $totalSalary = 0;
                            $totalPaid = 0;
                            $totalDue = 0;
                        @endphp
                        <tbody>

                            @foreach($employeeSalaries as $employeeSalary)
                            @php
                                $total  = $employeeSalary->employee?->total_salary ?? 0;
                                $paid = \App\Models\EmployeeSalaryLog::where('employee_id', $employeeSalary->employee_id)
                                        ->where('salary_month_id', $employeeSalary->salary_month_id)
                                        ->sum('paid');
                                $due = $total - $paid;


                                $empTotal = $employeeSalary->employee?->total_salary;
                                $empPaid = $employeeSalary->employee?->employee_salary_log->sum('paid');
                                $empDue = $empTotal - $empPaid;

                                $totalBasic = $totalBasic + $employeeSalary->employee?->basic_salary;
                                $totalTaDaOther = $totalTaDaOther + $employeeSalary->employee?->bill_allowance;
                                $totalSalary = $totalSalary + $employeeSalary->employee?->total_salary;
                                $totalPaid = $totalPaid + $paid;
                                $totalDue = $totalDue + $due;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $employeeSalary->salary_month)->format('F Y') }}</td>
                                <td>{{ $employeeSalary->employee?->name }}</td>
                                <td>{{ $employeeSalary->employee?->phone }}</td>
                                <td>{{ number_format($employeeSalary->employee?->basic_salary,2) }}</td>
                                <td>{{ number_format($employeeSalary->employee?->bill_allowance,2) }}</td>
                                <td>{{ number_format($employeeSalary->employee?->total_salary,2) }}</td>
                                <td>{{ number_format($paid,2) }}</td>
                                <td>
                                    <span class="{{ $due > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                        {{ number_format($due,2) }}
                                    </span>
                                </td>
                                <td class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="javascript:;" data-bs-toggle="modal"
                                        data-bs-target="#paymentModal{{ $employeeSalary->employee_id }}"
                                        class="btn btn-sm btn-primary waves-effect waves-light d-flex justify-content-center align-items-center gap-1"
                                        title="Due Release Payment"><i class="fas fa-money-check"></i>
                                        Payment</a>

                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="paymentModal{{ $employeeSalary->employee_id }}" tabindex="-1"
                                role="dialog" aria-labelledby="exampleModalScrollableTitle"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <form action="{{ route('salaries.releasePayment') }}" method="POST">
                                        @csrf
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Paid History
                                                </h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">

                                                <!-- Summary -->
                                                <div
                                                    class="d-flex justify-content-between mb-3 p-3 bg-light  border rounded shadow">
                                                    <div><strong>Total:</strong>
                                                        {{ number_format($empTotal) }}</div>
                                                    <div><strong>Paid:</strong>
                                                        {{ number_format($empPaid) }}</div>
                                                    <div><strong>Due:</strong> <span
                                                            class="text-danger">{{ number_format($empDue) }}</span>
                                                    </div>
                                                </div>

                                                <!-- Due Payment Form -->
                                                <div class="mb-4 p-3 bg-light border rounded shadow-sm">
                                                    <h6 class="mb-3"><strong>Pay Due Amount</strong>
                                                    </h6>


                                                    <input type="hidden" name="salary_month_id"
                                                        value="{{ $employeeSalary->salary_month_id }}">
                                                    <input type="hidden" name="employee_id"
                                                        value="{{ $employeeSalary->employee_id }}">

                                                    <div class="row g-3">
                                                        <!-- Amount Input -->
                                                        <div class="col-md-6">
                                                            <label
                                                                for="paid_amount_{{ $employeeSalary->employee_id }}"
                                                                class="form-label">Amount to Pay</label>
                                                            <input type="number" name="paid"
                                                                id="paid_amount_{{ $employeeSalary->employee_id }}"
                                                                class="form-control"
                                                                max="{{ $empDue }}"
                                                                min="1" required
                                                                placeholder="Enter paid amount" required>
                                                        </div>

                                                        <!-- Payment Date -->
                                                        <div class="col-md-6">
                                                            <label
                                                                for="payment_date_{{ $employeeSalary->employee_id}}"
                                                                class="form-label">Payment Date</label>
                                                            <input type="date" name="date"
                                                                id="payment_date_{{ $employeeSalary->employee_id }}"
                                                                class="form-control"
                                                                value="{{ date('Y-m-d') }}" required>
                                                        </div>

                                                        <!-- Payment Note -->
                                                        <div class="col-12">
                                                            <label for="note_{{ $employeeSalary->employee_id }}"
                                                                class="form-label">Note
                                                                (optional)</label>
                                                            <textarea name="note" id="note_{{ $employeeSalary->employee_id }}" class="form-control" rows="2" placeholder="Add a note"></textarea>
                                                        </div>
                                                    </div>

                                                </div>

                                                <!-- Payment History -->
                                                <div class="payment-history">
                                                    @foreach ($employeeSalary->employee->employee_salary_log as $index => $payment)
                                                        <div
                                                            class="card mb-3 shadow-sm bg-light border-left-4">
                                                            <div class="card-body p-3">

                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-2">
                                                                    <div>
                                                                        <strong>Payment
                                                                            #{{ $index + 1 }}</strong>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6 mb-2">
                                                                        <strong>Paid:</strong>
                                                                        {{ number_format($payment->paid) }}
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <strong>Due After
                                                                            Payment:</strong>
                                                                        {{ number_format($payment->due) }}
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <strong>Date:</strong>
                                                                        {{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}
                                                                    </div>
                                                                    <div class="col-md-6 mb-2">
                                                                        <strong>Note:</strong>
                                                                        {{ $payment->note ?? '-' }}
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success">Submit
                                                    Payment</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                        <tfoot class="fw-bold bg-light">
                            <tr>
                                <td colspan="4" class="text-end">Grand Total :</td>
                                <td>{{ number_format($totalBasic,2) }}</td>
                                <td>{{ number_format($totalTaDaOther,2) }}</td>
                                <td>{{ number_format($totalSalary,2) }}</td>
                                <td>{{ number_format($totalPaid,2) }}</td>
                                <td>
                                    <span class="{{ $totalDue > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($totalDue,2) }}
                                    </span>
                                </td>
                                <td></td>
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
    });


});
</script>
@endpush
