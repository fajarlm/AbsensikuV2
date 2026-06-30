<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'attachment',
        'status',
        'teacher_note',
    ];

    /**
     * Relasi ke Student
     * Satu pengajuan dimiliki oleh satu student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
