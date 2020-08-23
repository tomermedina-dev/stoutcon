<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Period;

class PeriodController extends Controller
{
    public function trial_balance_store(Request $request){

    	 $payslip  = Period::where('period',$request->month)->count();


               $url = url('admin/trial/balance/period/month/'.$request->month);

               $month_year = date('F Y',strtotime('01-'.str_replace("/", "-",$request->month)));

                $response = [
                  'success' => true,
                  'intended' => $url,
                  'page' => '_self',
                  'message' => 'Generated '.$month_year.' data!',
                  'data' => $request->all(),
              ];
              return response($response, 200);

    }

     public function income_statement_store(Request $request){

       $payslip  = Period::where('period',$request->month)->count();


               $url = url('admin/income/statement/period/month/'.$request->month);

               $month_year = date('F Y',strtotime('01-'.str_replace("/", "-",$request->month)));

                $response = [
                  'success' => true,
                  'intended' => $url,
                  'page' => '_self',
                  'message' => 'Generated '.$month_year.' data!',
                  'data' => $request->all(),
              ];
              return response($response, 200);

    }

     public function balance_sheet_store(Request $request){

       $payslip  = Period::where('period',$request->month)->count();


               $url = url('admin/balance/sheet/period/month/'.$request->month);

               $month_year = date('F Y',strtotime('01-'.str_replace("/", "-",$request->month)));

                $response = [
                  'success' => true,
                  'intended' => $url,
                  'page' => '_self',
                  'message' => 'Generated '.$month_year.' data!',
                  'data' => $request->all(),
              ];
              return response($response, 200);

    }
}
