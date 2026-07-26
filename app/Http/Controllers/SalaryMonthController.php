<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSalary;
use App\Models\EmployeeSalaryLog;
use App\Models\SalaryMonth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalaryMonthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salaries = SalaryMonth::latest()->get();
        return view('backend.pages.employee.salaries',compact('salaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $employees = Employee::latest()->get();
        $salaryMonth = SalaryMonth::create([
            'month_year'     => $request->salary_month, // already Y-m
            'total_employee' => $employees->count(),
        ]);
        $employeeSalaries = [];
        foreach ($employees as $employee) {
            $employeeSalaries[] = [
                'salary_month_id' => $salaryMonth->id,
                'employee_id'     => $employee->id,
                'salary_month'    => $salaryMonth->month_year,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }
        EmployeeSalary::insert($employeeSalaries);

        return redirect()->route('salaries.index')->with('Salary Opening Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employeeSalaries = EmployeeSalary::with('employee')->where(['salary_month_id'=>$id])->get();
        return view('backend.pages.employee.employee_salary',compact('employeeSalaries'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salaries = SalaryMonth::findOrFail($id);
        $salaries->delete();
        return redirect()->route('salaries.index')->with('message','Salary month deleted successfully');
    }

    public function release_payment(Request $request){
        $employee = Employee::findOrFail($request->employee_id);
        $totalSalary = $employee->total_salary;

        // total already paid for this employee & month
        $totalPaid = EmployeeSalaryLog::where('employee_id', $employee->id)
            ->where('salary_month_id', $request->salary_month_id)
            ->sum('paid');

        $newPaid = $totalPaid + $request->paid;

        // prevent over payment
        if ($newPaid > $totalSalary) {
            return back()->with('error', 'Payment exceeds total salary!');
        }

        $due = $totalSalary - $newPaid;

        EmployeeSalaryLog::create([
            'employee_id'     => $employee->id,
            'salary_month_id' => $request->salary_month_id,
            'paid'            => $request->paid,
            'due'             => $due,
            'date'            => $request->date,
            'note'            => $request->note,
        ]);
        return back()->with('message', 'Salary payment released successfully');
    }
}
