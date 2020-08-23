<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Biologs extends Model
{	
	protected $fillable = [
		'MachineNumber',
		'IndRegID',
		'DwInOutMode',
		'DateTimeRecord',
		'DateOnlyRecord',
		'TimeOnlyRecord',
	];

    protected $table = 'biologs';
}
