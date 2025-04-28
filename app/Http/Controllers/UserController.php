<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['pelanggan', 'karyawan'])->get();
        return view('be.user.index', compact('users'));
    }

    public function create()
    {
        return view('be.user.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'level' => 'required',
            'aktif' => 'required|boolean',
            'name' => 'required',
        ];

        // Validasi tambahan sesuai level
        if ($request->level == 'pelanggan') {
            $rules['nama_lengkap'] = 'required';
            $rules['no_hp_pelanggan'] = 'required';
            $rules['alamat_pelanggan'] = 'required';
        } else {
            $rules['nama_karyawan'] = 'required';
            $rules['no_hp_karyawan'] = 'required';
            $rules['alamat_karyawan'] = 'required';
            $rules['jabatan'] = 'required';
        }

        $data = $request->validate($rules);

        $user = User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'level' => $data['level'],
            'aktif' => $data['aktif'],
            'name' => $data['name'],
            'no_hp' => $data['level'] == 'pelanggan' ? $data['no_hp_pelanggan'] : $data['no_hp_karyawan'],
        ]);
        
        // Buat data pelanggan/karyawan sesuai level
        if ($data['level'] == 'pelanggan') {
            Pelanggan::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'no_hp' => $data['no_hp_pelanggan'],
                'alamat' => $data['alamat_pelanggan'],
                'id_user' => $user->id
            ]);
        } else {
            Karyawan::create([
                'nama_karyawan' => $data['nama_karyawan'],
                'no_hp' => $data['no_hp_karyawan'],
                'alamat' => $data['alamat_karyawan'],
                'jabatan' => $data['jabatan'],
                'id_user' => $user->id
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil ditambah');
    }

    public function edit($id)
    {
        $user = User::with(['pelanggan', 'karyawan'])->findOrFail($id);
        return view('be.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'email' => 'required|email',
            'level' => 'required',
            'aktif' => 'required|boolean',
            'name' => 'required',
        ]);
        $user->update($data);

        // Update data pelanggan/karyawan jika ada
        if ($user->level == 'pelanggan' && $user->pelanggan) {
            $user->pelanggan->update([
                'nama_lengkap' => $request->nama_lengkap,
                'no_hp' => $request->no_hp_pelanggan,
                'alamat' => $request->alamat_pelanggan,
            ]);
        } elseif ($user->karyawan) {
            $user->karyawan->update([
                'nama_karyawan' => $request->nama_karyawan,
                'no_hp' => $request->no_hp_karyawan,
                'alamat' => $request->alamat_karyawan,
                'jabatan' => $request->jabatan,
            ]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Hapus relasi jika perlu
        if ($user->pelanggan) $user->pelanggan->delete();
        if ($user->karyawan) $user->karyawan->delete();
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}