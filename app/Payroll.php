<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
  
    protected $dates = ['deleted_at'];
    protected $softDelete = true;
	protected $fillable = [

				'user_id',
				'month_year',
				'days',
				'basic_salary',
				'nights',
				'night_differencial',
				'total_basic_salary',
				'overtime',
				'benefits',
				'other_benefits',
				'gross_pay',
				'sss',
				'philhealth',
				'pag_ibig',
				'tax',
				'tardiness',
				'total_deduction',
				'net_pay',
            ];
    protected $table = 'payroll';
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

}
