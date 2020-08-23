<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeStatement extends Model
{
    use SoftDeletes;

      protected $fillable = [
			'period',
			'trial_balanace_id',
			'account',
			'type',
			//'category',
			'status',
			'amount',
	];

    protected $table = 'income_statement';
}
