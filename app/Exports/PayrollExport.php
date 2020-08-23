<?php

namespace App\Exports;

use App\Payroll;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use App\User;
use DB;


class PayrollExport implements FromQuery,WithHeadings
{


    public function query()
    {

    
        return User::query()->select(
            'id',
            'name',
            'salary_amount',
            'sss_contribution',
            'philhealth',
            'pag_ibig',
            'tax_withheld',
            'benefits as benefits',
            'other_benefits as other_benefits',

            DB::raw(
        
                // $this->year.' as month_year',

                '"" as days',
                '"" as basic_salary',
                '"" as nights',
                '"" as night_differencial',
                // '"" as total_basic_salary',
                '"" as overtime',
             
                // '"" as hdmf', 

                '"" as tardiness'
                // '"" as total_deduction',

                // '"" as net_pay'

            )

         

            )->where('status',1)->where('role',0);//->whereYear('created_at', $this->year);
    }

    public function headings():array {
    	   
           return [
                'ID',
                'Name',
                'Total Basic Salary',
                'SSS',
                'Philhealth',
                'Pag-Ibig',
                'Tax',
                'Benefits',
                'Other Benefits',
                'Day Shift (Days)',
                'Basic Salary',
                'Night Shift (Days)',
                'Night Differencial',
                
                'Overtime',
        
                // 'Gross Pay',
                
                
                'Tardiness',
                // 'Total Deduction',
                // 'Net Pay',
           ];
    }



   
}
