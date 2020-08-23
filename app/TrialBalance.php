<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrialBalance extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
		'period',
		'account',
		'tool',
		'category',
		'type',
		'status',
		'amount',
	];

    protected $table = 'trial_balance';
}
