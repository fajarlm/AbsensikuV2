<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nip',
        'status',
    ];

    /**
     * Relasi ke User
     * Satu teacher pasti punya satu user (akun login)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Subject (nanti kalau ada table subjects)
     * Satu guru bisa ngajar banyak mapel
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
