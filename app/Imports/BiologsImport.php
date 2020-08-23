<?php
namespace App\Imports;
use App\Biologs;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;
    
class BiologsImport implements ToModel,WithHeadingRow
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

        return new Biologs(array_merge($this->data,[
                'DwInOutMode' => $row['dwinoutmode'],
                'DateTimeRecord' => date('Y-m-d H:i:s',strtotime($row['dateonlyrecord'].' '.$row['timeonlyrecord'])),//
                'DateOnlyRecord' => date('Y-m-d',strtotime($row['dateonlyrecord'])),
                'TimeOnlyRecord' => date('H:i:s',strtotime($row['timeonlyrecord'])),
         ]));
    }
}
