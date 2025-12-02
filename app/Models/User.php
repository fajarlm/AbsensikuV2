<?php

namespace App\Models;

use Filament\Tables\Columns\Layout\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable,SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'gender',
        'profile'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    
    // public function getAuthIdentifierName()
    // {
    //     return 'username';
    // }

    // ✅ Wajib buat Filament: siapa aja yang boleh login ke panel
    // public function canAccessPanel(Panel $panel): bool
    // {
    //     // Lo bisa ganti logic-nya:
    //     // return $this->role === 'admin';
    //     return true; // sementara semua user bisa login
    // }
    
    // 🔹 Relasi ke Student
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // 🔹 Relasi ke Teacher
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }
}
 