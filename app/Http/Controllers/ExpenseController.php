<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $expenses = Expense::latest()->get();
        return view('backend.pages.expense.index',compact('expenses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->expense_id) {
            $expense = Expense::findOrFail($request->expense_id);
            $expense->update($request->only('reason', 'cost', 'date'));
            $message = 'Expense updated successfully';
        } else {
            Expense::create($request->only('reason', 'cost', 'date'));
            $message = 'Expense created successfully';
        }

        return redirect()->route('expenses.index')->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return redirect()->route('expenses.index')->with('message','Expense deleted successfully');
    }
}
