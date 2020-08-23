<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class BalanceSheet extends Model
{
   
	
	use SoftDeletes;
	
    protected $fillable = [
		'period',
		'trial_balanace_id',
		'account',
		'type',
		'category',
		'status',
		'amount',
	];

    protected $table = 'balance_sheet';




}
