<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SalaryMonth;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('backend.pages.employee.index',compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only(
            'name',
            'phone',
            'address',
            'joining_date',
            'basic_salary',
            'bill_allowance'
        );

        $data['total_salary'] = ($request->basic_salary ?? 0) + ($request->bill_allowance ?? 0);

        if ($request->employee_id) {
            $employee = Employee::findOrFail($request->employee_id);
            $employee->update($data);
            $message = 'Employee updated successfully';
        } else {
            Employee::create($data);
            $message = 'Employee created successfully';
        }

        return redirect()->route('employees.index')->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('employees.index')->with('message','Employee deleted successfully');
    }
}
