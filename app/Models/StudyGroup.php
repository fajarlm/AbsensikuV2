<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'grade',
        'major',
        'class_number',
    ];

    // Relasi ke Student (nantinya Student bisa belongTo StudyGroup)
    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
