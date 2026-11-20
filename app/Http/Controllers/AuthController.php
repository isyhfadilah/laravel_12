<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nim' => ['required', 'string', 'max:20', 'unique:mahasiswas,nim'],
            'email' => ['required', 'email', 'max:255', 'unique:mahasiswas,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // $mahasiswa = Mahasiswa::where('nim', $data['nim'])->where('email', $data['email'])->first();

        // if(!$mahasiswa || $mahasiswa->password !== null) {
        //     return back()->withErrors([
        //         'nim' => 'NIM atau email tidak valid, atau akun sudah terdaftar.'
        //     ])->onlyInput('nim', 'email');
        // }

        // $mahasiswa->password = Hash::make($data['password']);
        // $mahasiswa->save();
        // return redirect('/login')
        //     ->with('success', 'Registrasi berhasil. Silakan login.');

        $mahasiswa = Mahasiswa::create($data);
        Auth::login($mahasiswa);
        $request->session()->regenerate();

        return redirect('/mahasiswa')->with('success', 'Registrasi berhasil. Selamat datang!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nim' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'nim' => 'NIM atau kata sandi salah.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/mahasiswa');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}