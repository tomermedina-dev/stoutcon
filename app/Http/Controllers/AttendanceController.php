<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use DataTables;
use App\User;
use DB;

class AttendanceController extends Controller
{
    public function index(Request $request){

    	 if (!Gate::allows('admin')) {
            return abort(401);
        }

 
        $data = DB::table('users')->select(DB::raw('*, FORMAT(salary_amount,2) as salary_amount'))->latest()->get();


        if($request->ajax()){

            return DataTables::of($data)
                 ->addColumn('status', function($data) {
                        if($data->status == '1'){
                            return 'Active';
                        }else{
                            return 'Inactive';
                        }
                    })
                   ->addColumn('action',function($data){
                      $button = '<div class="btn-group">';
                      $button .= '<a class="btn btn-info btn-xs view" id="'.$data->id.'" data-action="'.url('admin/accounts/'.$data->id.'/show').'" ><i class="fas fa-eye"></i> View </a>';
                      $button .= '<a class="btn btn-warning btn-xs edit" id="'.$data->id.'" data-action="'.url('admin/accounts/'.$data->id.'/edit').'" ><i class="fas fa-pencil-alt"></i> Edit </a>';
                      $button .= '<a class="btn btn-danger btn-xs delete" id="'.$data->id.'" data-action="'.url('admin/accounts/'.$data->id.'/delete').'" ><i class="fas fa-trash"></i> Delete </a>';
                      $button .= '</div>';
                      return $button;

                   })
                   ->rawColumns(['action'])
                   ->make(true);
        }

        return view('attendance.index');
    }
}
