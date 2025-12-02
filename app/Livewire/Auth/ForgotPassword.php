<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
   
    public $username = '';
    public $verification_code = '';
    public $password = '';
    public $password_confirmation = '';
    public $step = 1; 
    public $verified_user_id = null;

    protected function rules()
    {
        if ($this->step === 1) {
            return [
                'username' => 'required|string',
                'verification_code' => 'required|string',
            ];
        }

        return [
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }

    protected $messages = [
        'username.required' => 'Username wajib diisi',
        'verification_code.required' => 'Kode verifikasi wajib diisi',
        'password.required' => 'Password baru wajib diisi',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'password.min' => 'Password minimal 6 karakter',
    ];

    public function verifyCode()
    {
        $this->validate();

        // Cari user berdasarkan username dan verification code
        $user = User::where('username', $this->username)
            ->whereHas('student', function ($query) {
                $query->where('verification_code', $this->verification_code);
            })
            ->first();

        if (!$user) {
            $this->addError('verification_code', 'Username atau kode verifikasi tidak valid');
            return;
        }

        // Simpan user ID untuk step berikutnya
        $this->verified_user_id = $user->id;
        $this->step = 2;
        
        session()->flash('verified', 'Kode verifikasi berhasil! Silakan masukkan password baru.');
    }

    public function resetPassword()
    {
        $this->validate();

        if (!$this->verified_user_id) {
            return redirect()->route('forgot-password');
        }

        $user = User::find($this->verified_user_id);
        
        if (!$user) {
            session()->flash('error', 'Terjadi kesalahan. Silakan coba lagi.');
            return redirect()->route('forgot-password');
        }

        // Update password
        $user->update([ 
            'password' => Hash::make($this->password)
        ]);

        // Optional: Clear verification code setelah digunakan
        if ($user->student) {
            $user->student->update(['verification_code' => null]);
        }

        session()->flash('success', 'Password berhasil direset! Silakan login dengan password baru.');
        return redirect()->route('login');
    }

    public function backToStep1()
    {
        $this->reset(['step', 'verified_user_id', 'password', 'password_confirmation']);
    }

    public function render()
    {
        // return view('livewire.auth.forgot-password', ['title' => 'Forgot Password'])->layout('layouts.auth');
        return view('livewire.auth.forgot-password', ['title' => 'Forgot Password']);
    }
}
