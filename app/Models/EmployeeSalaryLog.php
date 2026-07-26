<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryLog extends Model
{
    protected $fillable = ['salary_month_id','employee_id','paid','due','date','note'];
}
