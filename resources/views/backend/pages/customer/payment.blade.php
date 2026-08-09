@extends('backend.layouts.master')

@push('title')
    Customer Payment
@endpush

@push('css')
    <link href="{{ asset('storage/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid mt-5">
            <div class="page-content-wrapper">
                <div class="row">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">
                                    Payment History — {{ $customer?->name }} ({{ $customer?->phone }})
                                </h4>
                                <a href="javascript:;" data-bs-toggle="modal"
                                    data-bs-target="#paymentModal{{ $customer->id }}"
                                    class="btn btn-sm btn-primary waves-effect waves-light d-flex justify-content-center align-items-center gap-1"
                                    title="Due Release Payment"><i class="fas fa-money-check"></i>
                                    Payment</a>
                            </div>

                            <div class="card-body">
                                <div style="width: 100%; overflow-x: auto;">
                                    <table id="datatable" class="table table-sm table-striped table-bordered"
                                        style="border-collapse: collapse; border-spacing: 0; ">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Sale ID</th>
                                                <th>Invoice Date</th>
                                                <th>Delivery Date</th>
                                                <th>Amount</th>
                                                <th>Discount</th>
                                                <th>Total Amount</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $totalSales = 0;
                                                $totalDis = 0;
                                                $grandTotal = 0;
                                            @endphp
                                            @forelse($sales as $sale)
                                                @php
                                                    $totalSales = $totalSales + $sale->total;
                                                    $totalDis = $totalDis + $sale->discount_amount;
                                                    $grandTotal = $grandTotal + $sale->grand_total;
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><a
                                                            href="{{ route('sales.show', $sale->id) }}">#{{ $sale->id }}</a>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($sale->order_date)->format('d M Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($sale->delivery_date)->format('d M Y') }}
                                                    </td>
                                                    <td>{{ number_format($sale->total) }}</td>
                                                    <td>
                                                        @if($sale->discount_type == 'fixed')
                                                            {{ number_format($sale->discount_amount, 2) }}
                                                        @elseif($sale->discount_type == 'percentage')
                                                            {{ rtrim(rtrim($sale->discount_value, '0'), '.') }}% ({{ number_format($sale->discount_amount, 2) }})
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($sale->grand_total) }}</td>
                                                    <td class="d-flex justify-content-center align-items-center gap-2">
                                                        <a href="{{ route('sales.print', $sale->id) }}"
                                                            class="btn btn-sm btn-secondary d-flex justify-content-center align-items-center gap-1"><i
                                                                class="fas fa-print"></i> Print</a>
                                                        <a href="{{ route('sales.show', $sale->id) }}"
                                                            class="btn btn-sm btn-info waves-effect waves-light d-flex justify-content-center align-items-center gap-1"
                                                            title="Sale View"><i class="fas fa-eye"></i> Sale</a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No payment found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Grand Total:</th>
                                                <th>{{ number_format($totalSales) }}</th>
                                                <th>{{ number_format($totalDis) }}</th>
                                                <th>{{ number_format($grandTotal) }}</th>
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

    <!-- Modal -->
    <div class="modal fade" id="paymentModal{{ $customer->id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <form action="{{ route('customers.releasePayment') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Payment History
                            <a href="javascript:void(0)">#{{ $customer->name }}</a>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        @php
                            $paid = $payments->sum('paid');
                            $due = $grandTotal - $paid;
                        @endphp
                        <!-- Summary -->
                        <div class="d-flex justify-content-between mb-3 p-3 bg-light  border rounded shadow">
                            <div><strong>Total:</strong>
                                {{ number_format($grandTotal) }}</div>
                            <div><strong>Paid:</strong>
                                {{ number_format($paid) }}</div>
                            <div><strong>Due:</strong> <span class="text-danger">{{ number_format($due) }}</span>
                            </div>
                        </div>

                        <!-- Due Payment Form -->
                        <div class="mb-4 p-3 bg-light border rounded shadow-sm">
                            <h6 class="mb-3"><strong>Pay Due Amount</strong></h6>
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                            <div class="row g-3">
                                <!-- Amount Input -->
                                <div class="col-md-6">
                                    <label for="paid_amount_{{ $customer->id }}" class="form-label">Amount to Pay</label>
                                    <input type="number" name="paid" id="paid_amount_{{ $customer->id }}"
                                        class="form-control" max="{{ $due }}" min="1" required
                                        placeholder="Enter paid amount">
                                </div>

                                <!-- Payment Date -->
                                <div class="col-md-6">
                                    <label for="payment_date_{{ $customer->id }}" class="form-label">Payment Date</label>
                                    <input type="date" name="payment_date" id="payment_date_{{ $customer->id }}"
                                        class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>

                                <!-- Payment Note -->
                                <div class="col-12">
                                    <label for="note_{{ $customer->id }}" class="form-label">Note
                                        (optional)
                                    </label>
                                    <textarea name="note" id="note_{{ $customer->id }}" class="form-control" rows="2" placeholder="Add a note"></textarea>
                                </div>
                            </div>

                        </div>

                        <!-- Payment History -->
                        <div class="payment-history" style="overflow-y: scroll; max-height: 300px;">
                            @foreach ($payments as $index => $payment)
                                @if ($payment->paid > 0)
                                    <div
                                        class="card mb-3 shadow-sm bg-light border-left-4 {{ $payment->payment_status == 'Initial Payment' ? 'border-success' : 'border-info' }}">
                                        <div class="card-body p-3" style="position: relative">
                                            <div class="" style="position: absolute; top:0px; right:0px;">
                                                <a href="javascript:;" class="btn btn-sm btn-info waves-effect waves-light"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#paymentEditModal{{ $payment->id }}"><i class="fas fa-edit"></i> Edit
                                                </a>

                                                <form action="{{ route('customers.releasePaymentDelete', $payment->id) }}" method="POST" style="display:inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this payment?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger waves-effect waves-light">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <strong>Payment #{{ $payment->id }}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Date:
                                                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</strong>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <strong>Paid:</strong>
                                                    {{ number_format($payment->paid) }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Note:</strong>
                                                    {{ $payment->note ?? '-' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <span
                                                        class="badge bg-{{ $payment->payment_status == 'Initial Payment' ? 'success' : 'info' }}">
                                                        {{ $payment->payment_status }}
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-sm btn-success">Submit
                            Payment</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

{{-- Edit Payment Modals (outside paymentModal) --}}
    @foreach ($payments as $payment)
        <div class="modal fade" id="paymentEditModal{{ $payment->id }}" tabindex="-1"
            role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('customers.releasePaymentUpdate') }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Payment #{{ $payment->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                <div class="col-md-6">
                                    <label class="form-label">Paid Amount</label>
                                    <input type="number" name="paid" class="form-control"
                                        value="{{ $payment->paid }}" max="{{ $due + $payment->paid }}" min="1" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Payment Date</label>
                                    <input type="date" name="payment_date" class="form-control"
                                        value="{{ $payment->payment_date }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Note</label>
                                    <textarea name="note" class="form-control" rows="2">{{ $payment->note }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-sm btn-primary">Update Payment</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script src="{{ asset('storage/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('storage/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                pageLength: 25,
                order: [
                    [1, 'desc']
                ]
            });
        });
    </script>
@endpush
