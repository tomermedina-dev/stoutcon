<?php

namespace App\Exports;

use App\Biologs;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class BiologsExport implements FromQuery,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Biologs::select('MachineNumber','IndRegID','DwInOutMode','DateTimeRecord','DateOnlyRecord','TimeOnlyRecord')->limit(0)->get();
    }
    public function headings():array {
    	   
           return [
                //'MachineNumber',
                // 'IndRegID',
                'DwInOutMode',
                // 'DateTimeRecord',
                'DateOnlyRecord',
                'TimeOnlyRecord'
           ];
    }

    // public function map($biologs)  : array {
    //     return [
    //                 // 'Custome text'.$biologs->MachineNumber,
    //                 // $biologs->IndRegID,
    //                 // $biologs->DwInOutMode,
    //                 // $biologs->DateTimeRecord,
    //                 // $biologs->DateOnlyRecord,
    //                 // $biologs->TimeOnlyRecord,
    //            ];
    // }

    // public function columnFormats() : array(){
    //     return [

    //     ];
    // }
}
