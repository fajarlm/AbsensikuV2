<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $username = '';
    public $password = '';
  

    public function login()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ], [
            'username.required' => 'Userusername harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $data = ['username' => $this->username, 'password' => $this->password];
        if (Auth::attempt($data)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai admin');
            }
            if (Auth::user()->role == 'teacher') {
                return redirect()->route('teacher.dashboard')->with('success', 'Berhasil login sebagai guru');
            } elseif (Auth::user()->role == 'student') {
                return redirect()->route('student.dashboard')->with('success', 'Login berhasil');
            }
        } else {
            return redirect()->back()->with('error', 'Username atau Password salah');
        }
    }

    public function render()
    {
        // return view('livewire.auth.login', ['title' => 'Login'])->layout('layouts.auth'); 
        return view('livewire.auth.login', ['title' => 'Login']); 
    }
}
