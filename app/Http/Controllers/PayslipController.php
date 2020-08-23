<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payroll;
use App\User;
use PDF;
use Mail;
use DB;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;


class PayslipController extends Controller
{
    public function pdf(Request $request, $id){
    	$payslip = Payroll::find($id);
    	$user = User::find($payslip->user_id);

    	$month_year = date('F Y',strtotime("01-".str_replace("/", "-",$payslip->month_year)));
    	// echo "<pre>";
    	// print_r($user);exit;
    	//return view('payroll.payslip',compact('payslip','user','month_year'));

		$pdf = PDF::loadView('payroll.payslip',compact('payslip','user','month_year'))->setPaper('A4');
		return $pdf->stream('payslip-'.$user->name.date('y-m'),'.pdf');
    }

    public function email($month,$year){
   //  	 $month_year = $month.'/'.$year;
   //  	 $data = DB::table('payslip')->select(DB::raw('*,FORMAT(gross_pay,2) as gross_pay,FORMAT(total_deduction,2) total_deduction,FORMAT(net_pay,2) net_pay'))->where('month_year',$month_year)->get();


   //  	 foreach ($data as $key => $value) {

			// $job= (new SendEmailJob())
			// ->delay(Carbon::now()->addSeconds(5));
			//     dispatch($job);

   //  	 	// $mail = Mail::send('payroll.payslip', $data, function($message) use($data)
	  //     //   {
	  //     //       $message->to(['email'=>$value->email])
	  //     //           ->bcc(getenv('MAIL_FROM'))
	  //     //           ->subject('User Verification Request');
	  //     //   });
	        
	  //     //   if ($mail)
	  //     //   {
	  //     //       return true;
	  //     //   }
   //  	 }
	    	
	    
        
        return false;

    }
}
