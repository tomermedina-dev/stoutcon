<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\TrialBalanceValidation;
use DB;
use DataTables;
use App\Period;
use App\TrialBalance;
use App\IncomeStatement;
use App\BalanceSheet;

class TrialBalanceController extends Controller
{
    public function index(Request $request){

    	if (!Gate::allows('admin')) {
            return abort(401);
        }


        $data = DB::table('trial_balance')->select(DB::raw('period'))->groupBy('period')->get();


        if($request->ajax()){

            return DataTables::of($data)
              
                   ->addColumn('action',function($data){
                      $button = '<div class="btn-group">';
                      $button .= '<a class="btn btn-info btn-xs view" target="_blank" href="'.url('admin/trial/balance/period/month/'.$data->period.'').'" ><i class="fas fa-eye"></i> View </a>';
                      $button .= '</div>';
                      return $button;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
        }

        return view('accounting.period_trial_balance');
    }

     public function store(TrialBalanceValidation $request){

               $url = url('admin/trial/balance/period/month/'.$request->month);

              // $month_year = date('F Y',strtotime('01-'.str_replace("/", "-",$request->month)));

                $data = TrialBalance::create($request->toArray());

                if($request->type == 4 || $request->type == 5){
                    $request['trial_balanace_id'] = $data->id;
                    IncomeStatement::create($request->toArray());
                }

                if($request->type == 1 || $request->type == 2 || $request->type == 3){
                    $request['trial_balanace_id'] = $data->id;
                    BalanceSheet::create($request->toArray());
                }

                  

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

    	 $data = DB::select('CALL get_trial_balance("'.$period.'")');

        if($request->ajax()){

            return DataTables::of($data)
              
                   ->addColumn('action',function($data){
                      $button = '<div class="btn-group">';
                      // $button .= '<a class="btn btn-info btn-xs view" id="'.$data->id.'" data-action="'.url('admin/trial/balance/'.$data->id.'/show').'" ><i class="fas fa-eye"></i> View </a>';
                       $button .= '<a class="btn btn-warning btn-xs edit" id="'.$data->id.'" data-action="'.url('admin/trial/balance/'.$data->id.'/edit').'" ><i class="fas fa-pencil-alt"></i> Edit </a>';
                       $button .= '<a class="btn btn-danger btn-xs delete" id="'.$data->id.'" data-action="'.url('admin/trial/balance/'.$data->id.'/delete').'" ><i class="fas fa-trash"></i> Delete </a>';
                      $button .= '</div>';
                      return $button;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
        }

        return view('accounting.trial_balance',compact('month_year','month','year','period'));
    }

      public function edit($id)
    {   
        if (!Gate::allows('admin')) {
            return abort(401);
        }

        $data = TrialBalance::find($id);


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

        $trial_balance = TrialBalance::findOrFail($id);
        $trial_balance->update($request->all());

        $income_statement = IncomeStatement::where('trial_balanace_id',$trial_balance->id)->first();
        if($income_statement){
          $income_statement = IncomeStatement::findOrFail($income_statement->id);
          $income_statement->update($request->all());
        }

        $balance_sheet = BalanceSheet::where('trial_balanace_id',$trial_balance->id)->first();
        if($balance_sheet){
          $balance_sheet = BalanceSheet::findOrFail($balance_sheet->id);
          $balance_sheet->update($request->all());
        }

        $response = [
            'success' => true,
            'intended' =>  false,
            'page' => '_self',
            'message' => $trial_balance->account.' succesfully updated!',
            'data' => $trial_balance,
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
        $trial_balance = TrialBalance::findOrFail($id);
        $trial_balance->delete();
        
        $income_statement = IncomeStatement::where('trial_balanace_id',$trial_balance->id)->first();
        if($income_statement){
          $income_statement = IncomeStatement::findOrFail($income_statement->id);
          $income_statement->delete();
        }
        
        $balance_sheet = BalanceSheet::where('trial_balanace_id',$trial_balance->id)->first();
        if($balance_sheet){
          $balance_sheet = BalanceSheet::findOrFail($balance_sheet->id);
          $balance_sheet->delete();
        }

        $msg = $trial_balance->account." has been successfully deleted!";
        $response = [
            'success' => false,
            'intended' => false,
            'page' => '_self',
            'message' => $msg,
            'data' => $trial_balance
        ];
        return response($response, 200);
    }


}
