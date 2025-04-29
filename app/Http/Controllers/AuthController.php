<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function login()
    {
        return view("auth.login");
    }

    // Show registration form
    public function registration()
    {
        return view("auth.registration");
    }

    // Handle user registration
    public function registerUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'no_hp' => 'required',
            'password' => 'required|min:8|max:12|confirmed',
            'level' => 'required|in:admin,bendahara,pemilik,pelanggan'
        ]);

        $user = new User();
        $user->name = $request->email; // Atau string kosong jika ingin
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->password = Hash::make($request->password);
        $user->level = $request->level;
        $user->aktif = 1;

        if ($user->save()) {
            Auth::login($user);
            $request->session()->put('loginId', $user->id);

            // Redirect ke form data diri sesuai level
            if ($user->level == 'pelanggan') {
                return redirect()->route('auth.pelangganDataDiriForm');
            } else {
                return redirect()->route('auth.karyawanDataDiriForm');
            }
        } else {
            return back()->withErrors(['email' => 'Gagal registrasi.']);
        }
    }

    // Form data diri pelanggan
    public function pelangganDataDiriForm()
    {
        return view('auth.pelanggan_data_diri');
    }

    public function pelangganDataDiriSimpan(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $user = Auth::user();
        $fotoPath = $request->file('foto')->store('pelanggan', 'public');
        Pelanggan::updateOrCreate(
            ['id_user' => $user->id],
            [
                'nama_lengkap' => $request->nama_lengkap,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
                'no_hp' => $user->no_hp
            ]
        );
        // Redirect ke dashboard sesuai level
        return redirect($this->redirectByLevel($user->level))->with('success', 'Data diri berhasil disimpan!');
    }

    // Form data diri karyawan
    public function karyawanDataDiriForm()
    {
        return view('auth.karyawan_data_diri');
    }

    public function karyawanDataDiriSimpan(Request $request)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'alamat' => 'required',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $user = Auth::user();
        $fotoPath = $request->file('foto')->store('karyawan', 'public');
        Karyawan::updateOrCreate(
            ['id_user' => $user->id],
            [
                'nama_karyawan' => $request->nama_karyawan,
                'alamat' => $request->alamat,
                'foto' => $fotoPath,
                'no_hp' => $user->no_hp,
                // Tidak mengisi jabatan di sini!
            ]
        );
        // Redirect ke dashboard sesuai level
        return redirect($this->redirectByLevel($user->level))->with('success', 'Data karyawan berhasil disimpan!');
    }

    // Handle login request
    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where('email','=', $request->email)->first();
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $request->session()->put('loginId', $user->id);

            // Redirect based on user role
            return redirect()->intended($this->redirectByLevel(Auth::user()->level));
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

    // Helper: redirect path by level
    private function redirectByLevel($level)
    {
        switch ($level) {
            case 'admin':
                return '/admin';
            case 'bendahara':
                return '/bendahara';
            case 'pemilik':
                return '/owner'; // <--- dashboard owner
            case 'pelanggan':
            default:
                return '/';
        }
    }
}