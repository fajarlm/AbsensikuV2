<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudyGroupSeeder extends Seeder
{
    public function run(): void
    {
        $grades = ['X', 'XI', 'XII'];
        $majors = ['PPLG', 'TJKT', 'DKV', 'PMN', 'MPLB', 'KLN', 'HTL'];
        $classNumbers = ['1', '2', '3', '4', '5', '6'];

        foreach ($grades as $grade) {
            foreach ($majors as $major) {
                foreach ($classNumbers as $num) {
                    DB::table('study_groups')->insertOrIgnore([
                        'grade'        => $grade,
                        'major'        => $major,
                        'class_number' => $num,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }
        }
    }
}
