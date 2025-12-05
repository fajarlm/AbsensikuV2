<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'student_id',
        'schedule_id',
        'attendance_date',
        'status',
        'note',
    ];

    /**
     * Relasi ke Student
     * Satu attendance record punya satu student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi ke Subject
     * Satu attendance record nyambung ke satu subject
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
