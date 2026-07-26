<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name','phone','address','joining_date','basic_salary','bill_allowance','total_salary'];

    public function employee_salary_log()
    {
        return $this->hasMany(EmployeeSalaryLog::class);
    }
}
