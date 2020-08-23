<?php
namespace App\Imports;
use App\ Payroll;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
    
class PayrollImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $data; 

    public function __construct(array $data = [])
    {
        $this->data = $data; 
    }


    public function model(array $row)
    { 


        return new  Payroll(array_merge($this->data,[
                'user_id' => $row['id'],

                'sss' => $row['sss'],
                'philhealth' => $row['philhealth'],
                'pag_ibig' => $row['pag_ibig'],
                'tax' => $row['tax'],
                'tardiness' => $row['tardiness'],

                'total_deduction' =>  ($row['sss'] + $row['philhealth'] + $row['pag_ibig'] +  $row['tax'] + $row['tardiness']),
                //'total_deduction' => $row['total_deduction'],
                
                
                'total_basic_salary' => $row['total_basic_salary'],
                'days' => $row['day_shift_days'],
                'basic_salary' => $row['basic_salary'],
                'nights' => $row['night_shift_days'],
                'night_differencial' => $row['night_differencial'],
                'overtime' => $row['overtime'],
                'benefits' => $row['benefits'],
                'other_benefits' => $row['other_benefits'],
                'gross_pay' => ($row['basic_salary'] + $row['night_differencial'] + $row['overtime'] + $row['benefits'] + $row['other_benefits']),

                'net_pay' => (($row['basic_salary'] + $row['night_differencial'] + $row['overtime'] + $row['benefits']  + $row['other_benefits'])) - ($row['sss'] + $row['philhealth'] + $row['pag_ibig'] +  $row['tax'] + $row['tardiness'])

         ]));
    }
}
