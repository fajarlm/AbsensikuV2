<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithMapping; 
use Maatwebsite\Excel\Concerns\WithStyles; 
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $no = 0;
    public function collection()
    {
        return Student::all();
    }
    public function headings():array {
        return [
            'No',
            'Nama',
            'Username',
            'NIS',
            'NISN',
            'Rombel',
            'tanggal dibuat',
        ];
    }
    public function map($user):array {
        $this->no++;
        return [
            $this->no,
            $user->user->name,
            $user->user->username,
            $user->nis,
            $user->nisn,
            $user->study_group->name,
            Carbon::parse($user->created)->format('d-m-Y'), 
        ];
    }
     public function styles(Worksheet $sheet)
    {
        // ambil row & col terakhir
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();

        // bikin range otomatis dari A1 sampe data terakhir
        $cellRange = "A1:{$lastCol}{$lastRow}";

        // apply border ke semua cell
        $sheet->getStyle($cellRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // (opsional) heading dibikin bold
        $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->setBold(true);

        return [];
    }   
}
