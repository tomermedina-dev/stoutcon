<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;

class DashboardController extends Controller
{
    public function index(){

    	$date = \Carbon\Carbon::now();
		$current_month =  $date->format('F'); // July
		$url_current_month_year = 'admin/generate/payroll/month/'.$date->month.'/'.$date->year;

		$employee_count = User::count();
		$graph_year = $date->format('Y');

		$month = $date->format('m');
		$year = $date->format('Y');

		//$month = str_pad($i, 2, '0', STR_PAD_LEFT);
		

		$debit = [];
		$credit = [];

		$pie_credit = 0;
		$pie_debit = 0;
		$pie = [];

		for ($i=1; $i < 13; $i++) { 

			$number = str_pad($i, 2, '0', STR_PAD_LEFT);
             
             $monthYear = $number.'/'.$graph_year;
             $data = [];
			 $data = DB::select('CALL get_trial_balance("'.$monthYear.'")');

			if($data){
				$total_credit = 0;
				$total_debit = 0;
				foreach ($data as $key => $value) {
					$total_credit += floatval(str_replace(",","",$value->credit));
					$total_debit += floatval(str_replace(",","",$value->debit));

					$pie_debit += floatval(str_replace(",","",$value->debit));
					$pie_credit += floatval(str_replace(",","",$value->credit));
				}
			}
		
			$credit[] = $total_credit;
			$debit[] = $total_debit;

		}

		$pie[] = $pie_debit;
		$pie[] = $pie_credit;

    	return view('dashboard.index',compact('current_month','employee_count','url_current_month_year','debit','credit','pie','year','month'));
    }
}
