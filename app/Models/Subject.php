<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id',
        'name',
        'code',
        'description',
    ];

    /**
     * Relasi ke Teacher
     * Satu subject diajarin oleh satu guru
     */ 
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Relasi ke Attendance (kalau ada tabel absensi)
     * Satu subject bisa punya banyak record absensi
     */
    public function schedules()
    {
        return $this->hasMany(schedule::class);
    }
}
