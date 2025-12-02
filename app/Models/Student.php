<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'study_group_id',
        'verification_code',
    ];

    /**
     * Relasi ke User
     * Satu student pasti punya satu akun user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Attendance (kalau ada tabel absensi)
     * Satu student bisa punya banyak record absensi
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }



    protected function casts()
    {
        return [
            'password' => 'hashed',
            'verification_code' => 'hashed',
        ];
    }
}
