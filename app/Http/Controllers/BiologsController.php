<?php
namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\BiologsExport;
use App\Imports\BiologsImport;
use App\Http\Requests\BiologsValidation;

class BiologsController extends Controller
{
     /**
    * @return \Illuminate\Support\Collection
    */
    public function importExportView()
    {

       $employee = \App\User::select('id','name')->get()->whereNotNull('name')->pluck('name', 'id')->prepend('Please Select', '');//

       return view('biologs.import',compact('employee'));
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function export() 
    {   

        return Excel::download(new BiologsExport, 'biologs.csv');
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(BiologsValidation $request)
    {
    
        $employee_id = $request->employee_id;
        $user = \App\User::find($employee_id);

        $date = date('ymd');
        $filename = $employee_id.'-'.$date.'-biologs.csv';

        
       $data = [
             'MachineNumber' => 1, 
             'IndRegID' => $user->id, 
        ]; 

        Excel::import(new BiologsImport($data),request()->file('file'));
           
         $response = [
            'success' => true,
            'intended' => url('admin/biometrics/import'),
            'page' => '_self',
            'message' => $user->name.' biometric data has been successlly upload!',
            'data' => $user,
        ];
        return response($response, 200);
    }
}
