<?php


namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $no = 0;
    public function collection()
    {
        return Attendance::with(['student.user', 'schedule.studyGroup', 'schedule.subject'])->get();
    }
    public function headings(): array
    {
        return ['No', 'Tanggal', 'Kelas', 'Siswa', 'Mata Pelajaran', 'Status', 'Catatan'];
    }
    public function map($attedance): array
    {
        $this->no++;
        return [
            $this->no,
            Carbon::parse($attedance->date)->translatedFormat('d F Y'),
            $attedance->schedule->studyGroup->major . ' '.$attedance->schedule->studyGroup->grade .'-'. $attedance->schedule->studyGroup->class_number,
            $attedance->student->user->name,
            $attedance->schedule->subject->name,
            $attedance->status,
            $attedance->note
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
