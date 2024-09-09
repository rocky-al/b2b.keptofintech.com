<?php

namespace App\Exports;
use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;


class StaffExport implements FromCollection,WithHeadings,WithMapping,WithEvents,WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    * 
    */
    public function headings():array{
        return[
            'Roles',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Address',
            'DOB',
            'Status',
            'Created Date',
           ];
        }

    public function collection()
        {
           $staff = Staff::with(['roles'])->where('id','!=', '1')->get();
           return $staff; 
        }

    public function map($staff):array{
        $role = $staff->roles;
        //dd($staff);
        return [
            
            $role[0]->name,
            $staff->first_name,
            $staff->last_name,
            $staff->email,
            $staff->phone,
            $staff->address,
            $staff->dob = date('d-m-Y',strtotime($staff->dob)),
            ($staff->status) ? "Active" : "in-Active",
            $staff->created_at->format('d-m-Y'),

        ];
    }

    public function registerEvents(): array
        {
            return [
                AfterSheet::class    => function(AfterSheet $event) {
                    $event->sheet->getDelegate()->mergeCells('A1:J2')->getStyle('A4:I4')
                                    ->getFont()
                                    ->setBold(true);
                     $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true)->setSize(20);

                    $event->sheet->getDelegate()->getStyle('A1')->getAlignment()
                   ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $event->sheet->getDelegate()->setCellValue('A1', 'ADMIN STAFF REPORT');





                },
            ];


        }


     

     public function startCell(): string
       {
        return 'A4';
      }
 
}
