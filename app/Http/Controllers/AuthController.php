<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

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
        ]);

        $user = new User();
        $user->name = '';
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;
        $user->password = Hash::make($request->password);
        $user->level = 'pelanggan';
        $user->aktif = 1;

        if ($user->save()) {
            Auth::login($user);
            $request->session()->put('loginId', $user->id);
            return redirect()->route('auth.pelangganDataDiriForm');
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
        $user->update(['name' => $request->nama_lengkap]);
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
            ]
        );
        $user->update(['name' => $request->nama_karyawan]);
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

    // Forgot Password: Show form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Forgot Password: Send reset link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetLink = url('/reset-password/' . $token . '?email=' . urlencode($request->email));
        Mail::raw("Klik link berikut untuk reset password: $resetLink", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Reset Password');
        });

        return back()->with('success', 'Link reset password sudah dikirim ke email Anda.');
    }

    // Forgot Password: Show reset form
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('token', 'email'));
    }

    // Forgot Password: Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Token tidak valid atau sudah kadaluarsa.']);
        }

        User::where('email', $request->email)
            ->update(['password' => bcrypt($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password berhasil direset. Silakan login.');
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
                return '/owner';
            case 'pelanggan':
            default:
                return '/';
        }
    }
}