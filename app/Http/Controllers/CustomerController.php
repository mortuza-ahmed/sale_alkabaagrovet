<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PaymentTransaction;
use App\Models\Sale;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::with('payment_transactions')->latest()->get();
        return view('backend.pages.customer.index',compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Customer::create($request->all());
        return redirect()->route('customers.index')->with('message','Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        return view('backend.pages.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Customer::findOrFail($id)->update($request->all());

    }

    public function getSales($customerId)
    {
        $sales = Sale::where('customer_id', $customerId)
            ->select('id')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json($sales);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('error','Customer updated successfully');
    }

    public function customer_payment($id){
        $customer = Customer::find($id);
        $sales = Sale::where('customer_id', $id)->orderBy('id','desc')->get();
        $payments = PaymentTransaction::where('customer_id', $id)->orderBy('id','asc')->get();
        return view('backend.pages.customer.payment',compact('customer','sales','payments'));
    }

    public function release_payment(Request $request){
        $customer_id = $request->customer_id;
        $paid_amount = $request->paid;
        $payment_date = $request->payment_date;
        $note = $request->note;
        PaymentTransaction::create([
            'customer_id' => $customer_id,
            'paid'        => $paid_amount,
            'payment_date'=> $payment_date,
            'payment_status' => 'Payment released',
            'note'        => $note,
        ]);
        return back()->with('message', 'Payment released successfully.');
    }

    public function release_payment_update(Request $request){
        $payment_id = $request->payment_id;
        $paid_amount = $request->paid;
        $payment_date = $request->payment_date;
        $note = $request->note;

        // Create new payment transaction
        PaymentTransaction::find($payment_id)->update([
            'paid'        => $paid_amount,
            'payment_date'=> $payment_date,
            'note'        => $note,
        ]);

        return back()->with('message', 'Payment released successfully.');
    }

    public function payment_reports(Request $request)
    {
        $customers = Customer::orderBy('id','asc')->get();
        $fromDate = '';
        $toDate = '';
        $customerId = '';
        $payments = [];

        if($request->isMethod('post')){
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $customerId = $request->customer_id;
            $query = PaymentTransaction::with('customer')->where(['customer_id'=> $customerId])->orderBy('id','asc');
            if($fromDate && $toDate){
                $query->whereBetween('payment_date', [$fromDate, $toDate]);
            }
            $payments = $query->get();
        }
        return view('backend.pages.customer.reports', compact('customers', 'payments', 'fromDate', 'toDate','customerId'));
    }

    public function payment_reports_print(Request $request)
    {
        $fromDate = $request->query('from_date') ?? '';
        $toDate = $request->query('to_date') ?? '';
        $customerId = $request->query('customer_id') ?? '';

        $query = PaymentTransaction::with('customer')->where(['customer_id'=> $customerId])->orderBy('id','asc');
        if($fromDate && $toDate){
            $query->whereBetween('payment_date', [$fromDate, $toDate]);
        }
        $payments = $query->get();
        return view('backend.pages.customer.reports_print', compact('payments', 'fromDate', 'toDate', 'customerId'));
    }
}
