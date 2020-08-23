<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\BalanceSheetValidation;
use DB;
use DataTables;
use App\Period;
use App\TrialBalance;
use App\IncomeStatement;
use App\BalanceSheet;

class BalanceSheetController extends Controller
{
      public function index(Request $request){

    	if (!Gate::allows('admin')) {
            return abort(401);
        }

        $data = DB::table('balance_sheet')->select(DB::raw('period'))->groupBy('period')->get();


        if($request->ajax()){

            return DataTables::of($data)
              
                   ->addColumn('action',function($data){
                      $button = '<div class="btn-group">';
                      $button .= '<a class="btn btn-info btn-xs view" target="_blank" href="'.url('admin/balance/sheet/period/month/'.$data->period.'').'" ><i class="fas fa-eye"></i> View </a>';
                      $button .= '</div>';
                      return $button;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
        }

        return view('accounting.period_balance_sheet');
    }

     public function store(BalanceSheetValidation $request){

               $url = url('admin/balance/sheet/period/month/'.$request->month);

              // $month_year = date('F Y',strtotime('01-'.str_replace("/", "-",$request->month)));

                  $request['trial_balanace_id'] = 0;
                $data =  BalanceSheet::create($request->toArray());
               
                  

                $response = [
                  'success' => false,
                  'intended' => false,
                  'page' => '_self',
                  'message' => $data->account.' trial balance has been successfully saved!',
                  'data' => $data,
              ];
              return response($response, 200);

    }

    public function selected_month(Request $request,$month,$year){


    	 $period = $month.'/'.$year;
    	 $month_year = date('F Y',strtotime($year.'-'.$month.'-01'));

    	 $data = DB::select('CALL get_balance_sheet("'.$period.'")');

        if($request->ajax()){

            return DataTables::of($data)
              
                   ->addColumn('action',function($data){
                      $button = '<div class="btn-group">';
                      // $button .= '<a class="btn btn-info btn-xs view" id="'.$data->id.'" data-action="'.url('admin/trial/balance/'.$data->id.'/show').'" ><i class="fas fa-eye"></i> View </a>';
                      if($data->id){
                          $button .= '<a class="btn btn-warning btn-xs edit" id="'.$data->id.'" data-action="'.url('admin/balance/sheet/'.$data->id.'/edit').'" ><i class="fas fa-pencil-alt"></i> Edit </a>';
                          $button .= '<a class="btn btn-danger btn-xs delete" id="'.$data->id.'" data-action="'.url('admin/balance/sheet/'.$data->id.'/delete').'" ><i class="fas fa-trash"></i> Delete </a>';
                      }
                     
                      $button .= '</div>';
                      return $button;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
        }

        return view('accounting.balance_sheet',compact('month_year','month','year','period'));
    }

      public function edit($id)
    {   
        if (!Gate::allows('admin')) {
            return abort(401);
        }

        $data = BalanceSheet::find($id);


        $response = [
            'success' => true,
            'intended' => false,
            'page' => '_self',
            'message' => $data->account.' succesfully pulled data!',
            'data' => $data,
        ];
        return response($response, 200);
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
        if (!Gate::allows('admin')) {
            return abort(401);
        }

        $balance_sheet = BalanceSheet::findOrFail($id);
        $balance_sheet->update($request->all());



        $response = [
            'success' => true,
            'intended' =>  false,
            'page' => '_self',
            'message' => $balance_sheet->account.' succesfully updated!',
            'data' => $balance_sheet,
        ];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $balance_sheet = BalanceSheet::findOrFail($id);
        $balance_sheet->delete();
       


        $msg = $balance_sheet->account." has been successfully deleted!";
        $response = [
            'success' => false,
            'intended' => false,
            'page' => '_self',
            'message' => $msg,
            'data' => $balance_sheet
        ];
        return response($response, 200);
    }


}
