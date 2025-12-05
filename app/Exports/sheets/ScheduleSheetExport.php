<?php

namespace App\Exports\Sheets;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScheduleSheetExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    private $classId;
    private $className;
    private $no = 0;

    public function __construct($classId, $className)
    {
        $this->classId = $classId;
        $this->className = $className; 
    }

    public function title(): string
    {
        return $this->className; 
    }

    public function collection()
    {
        return Schedule::with('subject', 'studyGroup')
            ->where('study_group_id', $this->classId)
            ->orderBy('day') // Urutkan berdasarkan hari
            ->orderBy('start_time') // Urutkan berdasarkan jam mulai
            ->get();
    }

    public function headings(): array
    {
        return [
            'No', 
            'Hari', 
            'Jam Mulai', 
            'Jam Selesai', 
            'Kelas', 
            'Mata Pelajaran', 
            'Status', 
            'Catatan',
            'Durasi'
        ];
    }

    public function map($schedule): array
    {
        $this->no++;
        
        // Konversi hari ke bahasa Indonesia
        $daysInIndonesian = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];
        
        $day = $daysInIndonesian[$schedule->day] ?? $schedule->day;
        
        // Hitung durasi
        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);
        $duration = $start->diff($end)->format('%H:%I') . ' jam';
        
        return [
            $this->no,
            $day,
            $schedule->start_time,
            $schedule->end_time,
            $schedule->studyGroup->major . ' ' . 
                $schedule->studyGroup->grade . '-' . 
                $schedule->studyGroup->class_number,
            $schedule->subject->name,
            $schedule->status ?? '-',
            $schedule->note ?? '-',
            $duration,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = $sheet->getHighestColumn();
        $cellRange = "A1:{$lastCol}{$lastRow}";

        // Border untuk semua sel
        $sheet->getStyle($cellRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Header bold
        $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->setBold(true);

        // Format jam
        $sheet->getStyle('C2:C' . $lastRow)->getNumberFormat()->setFormatCode('hh:mm');
        $sheet->getStyle('D2:D' . $lastRow)->getNumberFormat()->setFormatCode('hh:mm');

        // Auto width untuk kolom
        foreach (range('A', $lastCol) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}