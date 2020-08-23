<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\MonthlyDataValidation;
use App\Http\Requests\BiologsMetricValidation;
use App\Http\Requests\MonthlyEmployeeDataValidation;
use App\Http\Requests\LeavedValidation;
use Carbon\Carbon as Carbon;
use App\Biologs;
use App\Biometrics;
use DataTables;
use App\User;
use App\Payroll;
use DB;
use Illuminate\Support\Str;
use DateTime;

class BiometricsController extends Controller
{
    
    public function import_biometrics(MonthlyEmployeeDataValidation $request){
        

         $employee_id = $request->employee_id;
         $month = $request->month;
         $user = \App\User::find($employee_id);

        $response = [
        'success' => true,
        'intended' => url('admin/biometrics/generate/'.$employee_id.'/'.$month),
        'page' => '_self',
        'message' => $user->name.' success generating record for the month of '.$month.'!',
        'data' => $user,
        ];
        return response($response, 200);


    }

    public function monthly_records(Request $request,$id,$month,$year){

  
        if (!Gate::allows('admin')) {
            return abort(401);
        }


        $month_year =  $month.'/'.$year;//date("Y-m-d", strtotime($year.'-'.$month.'-1'));
        $user = \App\User::find($id);
      
        $data = DB::table('biologs')->where('IndRegID',$id)->whereMonth('DateOnlyRecord', '=', $month)->whereYear('DateOnlyRecord', '=', $year)->get()->sortByDesc('DateTimeRecord');

        // echo "<pre>";
        // print_r($data);exit;

        if($request->ajax()){

            return DataTables::of($data)
                ->addColumn('status', function($data) {
                      switch ($data->status) {
                        case 1:
                          return 'Leaved w/ Pay';
                          break;
                         case 2:
                          return 'Leaved w/out Pay';
                          break;
                        
                        default:
                          # code...
                          break;
                      }
                  })
                  ->addColumn('TimeOnlyRecord', function($data) {
                      return date('h:i A', strtotime($data->TimeOnlyRecord));
                  })
                  ->addColumn('DateOnlyRecord', function($data) {
                      return date('M d, Y', strtotime($data->DateOnlyRecord));
                  })
                  ->addColumn('DwInOutMode', function($data) {

                      switch ($data->DwInOutMode) {
                            case 0:
                                return 'Time In';
                            break;
                            case 1:
                                return 'Time Out';
                            break;
                            case 2:
                                return 'Absent';
                            break;
                            case 3:
                                return 'Leaved w/ Pay';
                            break;
                            case 4:
                                return 'Leaved w/out Pay';
                            break;

                          default:
                              # code...
                              break;
                      }
                       
                    })
                   ->addColumn('action',function($data){

                      $button = '<div class="btn-group">';
                      $button .= '<a class="btn btn-danger btn-xs delete" id="'.$data->id.'" data-action="'.url('admin/biometric/'.$data->id.'/delete').'" ><i class="fas fa-trash"></i> Delete </a>';
                      $button .= '</div>';
                      return $button;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
        }

        return view('biometrics.monthly_data',compact('user','data','month_year'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('admin')) {
            return abort(401);
        }

        $employee = \App\User::select('id','name')->get()->whereNotNull('name')->pluck('name', 'id')->prepend('Please Select', '');

       // $data = DB::table('users')->latest()->get();

        // if($request->ajax()){
    
        //     return DataTables::of($data)
        //            ->addColumn('action',function($data){
        //               $button = '<div class="btn-group">';
    
        //               $button .= '<a class="btn btn-warning btn-xs edit" id="'.$data->id.'" data-action="'.url('admin/accounts/'.$data->id.'/edit').'" ><i class="fas fa-pencil-alt"></i> Edit </a>';
                     
        //               $button .= '</div>';
        //               return $button;

        //            })
        //            ->rawColumns(['action'])
        //            ->make(true);
        // }


        return view('biometrics.index',compact('employee'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BiologsMetricValidation $request)
    {

       
        if (!Gate::allows('admin')) {
            return abort(401);
        }

         $data = [
            'MachineNumber' => 1, 
            'IndRegID' => $request->user_id, 
            'DwInOutMode' => $request->DwInOutMode,
            'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($request->DateOnlyRecord.' '.$request->TimeOnlyRecord)),//
            'DateOnlyRecord' => date('Y-m-d',strtotime($request->DateOnlyRecord)),
            'TimeOnlyRecord' => date('H:i:s',strtotime($request->TimeOnlyRecord)),
           ];


        $user = User::find($request->user_id);
        
        $payroll = Biologs::create($data);

        $response = [
            'success' => true,
            'intended' => url('admin/biometrics/generate/'.$request->user_id.'/'.$request->month_year),
            'page' => '_self',
            'message' => $user->name.' biometric information has been successfully added!',
            'data' => $payroll,
        ];

        return response($response, 200);

    }


    public function leaved(LeavedValidation $request)
    {

    
        $date = $request->date_range;
        $collection = Str::of($date)->explode(',');


        foreach ($collection as $key => $value) {


           switch ($request->shift) {
                case 1:
                    $time_in = '06:00 AM';
                    $time_out = '02:00 PM';
                    
                    $data = [
                        'MachineNumber' => 1, 
                        'IndRegID' => $request->user_id, 
                        'DwInOutMode' => 0,
                        'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($value.' '.$time_in)),//
                        'DateOnlyRecord' => date('Y-m-d',strtotime($value)),
                        'TimeOnlyRecord' => date('H:i:s',strtotime($time_in)),
                        'status' => $request->leaved,
                    ];


                    $dataArry[] = $data;


                    $data = [
                        'MachineNumber' => 1, 
                        'IndRegID' => $request->user_id, 
                        'DwInOutMode' => 1,
                        'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($value.' '.$time_out)),//
                        'DateOnlyRecord' => date('Y-m-d',strtotime($value)),
                        'TimeOnlyRecord' => date('H:i:s',strtotime($time_out)),
                        'status' => $request->leaved,
                    ];


                    $dataArry[] = $data;


                    break;
                case 2:
                    $time_in = '02:00 PM';
                    $time_out = '10:00 PM';


                    $data = [
                        'MachineNumber' => 1, 
                        'IndRegID' => $request->user_id, 
                        'DwInOutMode' => 0,
                        'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($value.' '.$time_in)),//
                        'DateOnlyRecord' => date('Y-m-d',strtotime($value)),
                        'TimeOnlyRecord' => date('H:i:s',strtotime($time_in)),
                        'status' => $request->leaved,
                    ];


                    $dataArry[] = $data;


                    $data = [
                        'MachineNumber' => 1, 
                        'IndRegID' => $request->user_id, 
                        'DwInOutMode' => 1,
                        'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($value.' '.$time_out)),//
                        'DateOnlyRecord' => date('Y-m-d',strtotime($value)),
                        'TimeOnlyRecord' => date('H:i:s',strtotime($time_out)),
                        'status' => $request->leaved,
                    ];


                    $dataArry[] = $data;



                    break;
                case 3:
                    $time_in = '10:00 PM';
                    $time_out = '02:00 AM';



                    $data = [
                        'MachineNumber' => 1, 
                        'IndRegID' => $request->user_id, 
                        'DwInOutMode' => 0,
                        'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($value.' '.$time_in)),//
                        'DateOnlyRecord' => date('Y-m-d',strtotime($value)),
                        'TimeOnlyRecord' => date('H:i:s',strtotime($time_in)),
                        'status' => $request->leaved,
                    ];


                    $dataArry[] = $data;


                    $data = [
                        'MachineNumber' => 1, 
                        'IndRegID' => $request->user_id, 
                        'DwInOutMode' => 1,
                        'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($value.' '.$time_out. ' +1 day')),//
                        'DateOnlyRecord' => date('Y-m-d',strtotime($value. ' +1 day')),
                        'TimeOnlyRecord' => date('H:i:s',strtotime($time_out)),
                        'status' => $request->leaved,
                    ];


                    $dataArry[] = $data;


                    break;
                default:
                    # code...
                    break;
            }

         

        }

       

    
        $user = User::find($request->user_id);
        
        $payroll = Biologs::insert($dataArry);

        $response = [
            'success' => true,
            'intended' => url('admin/biometrics/generate/'.$request->user_id.'/'.$request->month_year),
            'page' => '_self',
            'message' => $user->name.' biometric information has been successfully added!',
            'data' => $payroll,
        ];

        return response($response, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $biometric = Biologs::findOrFail($id);
        $user = User::find($biometric->IndRegID);
        $biometric->delete();
        $msg = $user->name." time record has been successfully deleted!";
        $response = [
            'success' => true,
            'intended' => false,
            'page' => '_self',
            'message' => $msg,
            'data' => $biometric
        ];
        return response($response, 200);
    }
}
