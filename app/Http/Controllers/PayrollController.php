<?php
namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\PayrollExport;
use App\Imports\PayrollImport;
use App\Http\Requests\MonthlyDataValidation;
use App\Http\Requests\MonthlyEmployeeDataValidation;
use App\Http\Requests\BiologsMetricValidation;
use Illuminate\Support\Facades\Gate;
use App\Biologs;
use DB;
use App\Payroll;
use DataTables;
use App\User;

class PayrollController extends Controller
{   

    public function monthly_records(Request $request,$id,$month,$year){

         if (!Gate::allows('admin')) {
            return abort(401);
        }

         $month_year =  $month.'/'.$year;//date("Y-m-d", strtotime($year.'-'.$month.'-1'));

         $data = DB::select('call biotimediff(?,?)',array($id,$month_year));

         $user = \App\User::find($id);

         $find = Payroll::where('user_id',$id)->where('month_year',$month_year)->first();

         if(!$find){
            $find = new \stdClass; 
            $find->overtime = "0";
            $find->tardiness = "0"; 
         }

 
        return view('payroll.create_monthly_payroll',compact('user','data','month_year','find'));
    }

    public function import_biometrics(MonthlyEmployeeDataValidation $request){
        

         $employee_id = $request->employee_id;
         $month = $request->month;
         $user = \App\User::find($employee_id);

        $response = [
        'success' => true,
        'intended' => url('admin/payroll/create/'.$employee_id.'/'.$month),
        'page' => '_self',
        'message' => $user->name.' success generating record for the month of '.$month.'!',
        'data' => $user,
        ];
        return response($response, 200);


    }


    public function create_payroll(Request $request){
   
        if (!Gate::allows('admin')) {
            return abort(401);
        }

        $employee = \App\User::select('id','name')->get()->whereNotNull('name')->pluck('name', 'id')->prepend('Please Select', '');

        return view('payroll.create_payroll',compact('employee'));
    }

    
    public function select_month(){

       return view('payroll.monthly_data');
    }

    public function biometric_store(BiologsMetricValidation $request)
    {
       
        if (!Gate::allows('admin')) {
            return abort(401);
        }


        // echo "<pre>";
        // print_r($request->all());exit;

      // [_token] => vJFCQm6s95boDn0OyS5ne70eqTZb2f7js1VcBAsL
      // [DwInOutMode] => 0
      // [absent] => 1
       //[shift] => 1
      // [DateOnlyRecord] => 2020-07-09
      // [TimeOnlyRecord] => 1:27 AM
      // [month_year] => 07/2020
      // [user_id] => 5

      // if(){


      // }else{

      //    // $data = [
      //    //    'MachineNumber' => 1, 
      //    //    'IndRegID' => $request->user_id, 
      //    //    'DwInOutMode' => $request->DwInOutMode,
      //    //    'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($request->DateOnlyRecord.' '.$request->TimeOnlyRecord)),//
      //    //    'DateOnlyRecord' => date('Y-m-d',strtotime($request->DateOnlyRecord)),
      //    //    'TimeOnlyRecord' => date('H:i:s',strtotime($request->TimeOnlyRecord)),
      //    // ];
      // }
   


        $user = User::find($request->user_id);
        
        $payroll = Biologs::create($data);

        $response = [
        'success' => true,
        'intended' => url('admin/payroll/create/'.$request->user_id.'/'.$request->month_year),
        'page' => '_self',
        'message' => $user->name.' biometric information has been successfully added!',
        'data' => $request->all(),
        ];

        return response($response, 200);

    }


    public function store(Request $request){

      $user = User::find($request->user_id);

      $request['days'] = $request->days_hours / 8;
      $request['nights'] = $request->night_hours / 8;
      $request['tax'] = $request->tax_withheld;
      $request['sss'] = $request->sss_contribution;
      $request['philhealth'] = $request->philhealth;
      $request['pag_ibig'] = $request->pag_ibig;
      $request['night_differencial'] = $request->hidden_basic_salary;
      $request['basic_salary'] = $request->hidden_basic_salary;
      $request['overtime'] = $request->overtime_pay;

      Payroll::where('user_id',$request->user_id)->where('month_year',$request->month_year)->delete();
      $payroll = Payroll::create($request->toArray());

       $response = [
                'success' => true,
                'intended' => url('admin/payroll/create/'.$request->user_id.'/'.$request->month_year),
                'page' => '_self',
                'message' => $user->name.' payroll information has been successfully added!',
                'data' => $request->all(),
            ];
          
      return response($response, 200);
    
    }

    public function load_data(Request $request){

         $payslip  = Payroll::where('month_year',$request->month)->count();

         if($payslip){


               $url = url('admin/generate/payroll/month/'.$request->month);

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
        

          $response = [
                  'success' => false,
                  'intended' => false,
                  'page' => '_self',
                  'message' => 'No data found!',
                  'data' => $request->all(),
              ];
              return response($response, 500);



    }

    public function index(Request $request,$month,$year){

        if (!Gate::allows('admin')) {
            return abort(401);
        }
        $month_year = $month.'/'.$year;
     
        $data = DB::table('payslip')->select(DB::raw('payslip_id,name,FORMAT(gross_pay,2) as gross_pay,FORMAT(total_deduction,2) total_deduction,FORMAT(net_pay,2) net_pay'))->where('month_year',$month_year)->get();

        if($request->ajax()){

            return DataTables::of($data)
                   ->addColumn('action',function($data){
                      $button = '<div class="btn-group">';
                      $button .= '<a class="btn btn-info btn-xs view" target="_blank" id="'.$data->payslip_id.'" href="'.route('admin.payslip.index',[$data->payslip_id]).'" ><i class="fas fa-file-pdf"></i> Generate Payslip </a>';
                      $button .= '</div>';
                      return $button;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
        }

        $month_year = date('F Y',strtotime($year.'-'.$month.'-01'));

        return view('payroll.index',compact('month','year','month_year'));
     
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function importExportView()
    {

       return view('payroll.export_view');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {   

        return Excel::download(new PayrollExport(), 'payroll.csv');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(MonthlyDataValidation $request)
    { 

    
        $data = [
             'month_year' => $request->month, 
        ]; 

        Excel::import(new PayrollImport($data),request()->file('file'));
           
         $response = [
            'success' => true,
            'intended' => url('admin/generate/payroll/month/'.$request->month),
            'page' => '_self',
            'message' => $request->month.' payroll data has been successlly upload!',
            'data' => $data,
        ];
        return response($response, 200);
    }
}
