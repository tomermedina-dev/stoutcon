<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use DB;

class PDFController extends Controller
{
    public function trial($month,$year){


		$period = $month.'/'.$year;
		$period = self::month_to_word($month).' '.$year;
		$data = DB::select('CALL get_trial_balance("'.$period.'")');

		$pdf = PDF::loadView('pdf.trial',compact('data','period'))->setPaper('a3', 'portrait')->setWarnings(false);

		return $pdf->stream();
    }

    public function income_statement($month,$year){
    	$period = $month.'/'.$year;
	
		$data = DB::select('CALL get_income_statement("'.$period.'")');
		$period = self::month_to_word($month).' '.$year;
		// echo "<pre>";
		// print_r($data);exit;

		$pdf = PDF::loadView('pdf.income_statement',compact('data','period'))->setPaper('a3', 'portrait')->setWarnings(false);

		return $pdf->stream();
    }

      public function balance_sheet($month,$year){
    	$period = $month.'/'.$year;
	
		$data = DB::select('CALL get_balance_sheet("'.$period.'")');

		$period = self::month_to_word($month).' '.$year;
		// echo "<pre>";
		// print_r($data);exit;
		//return view('pdf.balance_sheet',compact('data','period'));
		$pdf = PDF::loadView('pdf.balance_sheet',compact('data','period'))->setPaper('a3', 'portrait')->setWarnings(false);

		return $pdf->stream();
    }

    public static function month_to_word($month){
    	switch ($month) {
    		case 1:
    			return 'January';
    			break;
    		case 2:
    			return 'February';
    			break;
    		case 3:
    			return 'March';
    			break;
    		case 4:
    			return 'April';
    			break;
    		case 5:
    			return 'May';
    			break;
    		case 6:
    			return 'June';
    			break;
    		case 7:
    			return 'July';
    			break;
    		case 8:
    			return 'August';
    			break;
    		case 9:
    			return 'September';
    			break;
    	    case 10:
    			return 'October';
    			break;
    		case 11:
    			return 'November';
    			break;
    		case 12:
    			return 'December';
    			break;
    		default:
    			# code...
    			break;
    	}
    }
}
