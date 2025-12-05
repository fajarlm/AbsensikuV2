<?php

namespace App\Exports;

use App\Exports\Sheets\ScheduleSheetExport;
use App\Models\StudyGroup;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ScheduleExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $groups = StudyGroup::all(); 

        foreach ($groups as $group) {
            $sheets[] = new ScheduleSheetExport($group->id, $group->major . ' ' . $group->grade . '-' . $group->class_number); 
        }

        return $sheets;
    }
}
