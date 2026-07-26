<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = ['salary_month_id','employee_id','salary_month'];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->with('employee_salary_log');
    }
}
