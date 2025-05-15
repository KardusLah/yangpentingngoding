<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = User::with(['pelanggan', 'karyawan']);

        if ($status == 'Aktif') {
            $query->where('aktif', 1);
        } elseif ($status == 'Nonaktif') {
            $query->where('aktif', 0);
        }

        $users = $query->get();
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
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

        // Proses upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('user', 'public');
        }

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
                'foto' => $fotoPath,
                'id_user' => $user->id
            ]);
        } else {
            Karyawan::create([
                'nama_karyawan' => $data['nama_karyawan'],
                'no_hp' => $data['no_hp_karyawan'],
                'alamat' => $data['alamat_karyawan'],
                'jabatan' => $data['jabatan'],
                'foto' => $fotoPath,
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

        $rules = [
            'email' => 'required|email',
            'level' => 'required',
            'aktif' => 'required|boolean',
            'name' => 'required',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

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

        // Proses upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('user', 'public');
        }

        $user->update([
            'email' => $data['email'],
            'level' => $data['level'],
            'aktif' => $data['aktif'],
            'name' => $data['name'],
            'no_hp' => $data['level'] == 'pelanggan' ? $data['no_hp_pelanggan'] : $data['no_hp_karyawan'],
        ]);

        // Update data pelanggan/karyawan jika ada
        if ($user->level == 'pelanggan' && $user->pelanggan) {
            $updateData = [
                'nama_lengkap' => $request->nama_lengkap,
                'no_hp' => $request->no_hp_pelanggan,
                'alamat' => $request->alamat_pelanggan,
            ];
            if ($fotoPath) {
                // Hapus foto lama jika ada
                if ($user->pelanggan->foto) {
                    Storage::disk('public')->delete($user->pelanggan->foto);
                }
                $updateData['foto'] = $fotoPath;
            }
            $user->pelanggan->update($updateData);
        } elseif ($user->karyawan) {
            $updateData = [
                'nama_karyawan' => $request->nama_karyawan,
                'no_hp' => $request->no_hp_karyawan,
                'alamat' => $request->alamat_karyawan,
                'jabatan' => $request->jabatan,
            ];
            if ($fotoPath) {
                if ($user->karyawan->foto) {
                    Storage::disk('public')->delete($user->karyawan->foto);
                }
                $updateData['foto'] = $fotoPath;
            }
            $user->karyawan->update($updateData);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Hapus relasi jika perlu
        if ($user->pelanggan) {
            if ($user->pelanggan->foto) {
                Storage::disk('public')->delete($user->pelanggan->foto);
            }
            $user->pelanggan->delete();
        }
        if ($user->karyawan) {
            if ($user->karyawan->foto) {
                Storage::disk('public')->delete($user->karyawan->foto);
            }
            $user->karyawan->delete();
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->aktif = $request->aktif ? 1 : 0;
        $user->save();
        return response()->json(['success' => true]);
    }

    public function bulkAction(Request $request, $action)
    {
        $ids = $request->input('selected', []);
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada user yang dipilih.');
        }

        if ($action === 'delete') {
            $users = User::whereIn('id', $ids)->get();
            foreach ($users as $user) {
                if ($user->pelanggan) {
                    if ($user->pelanggan->foto) {
                        Storage::disk('public')->delete($user->pelanggan->foto);
                    }
                    $user->pelanggan->delete();
                }
                if ($user->karyawan) {
                    if ($user->karyawan->foto) {
                        Storage::disk('public')->delete($user->karyawan->foto);
                    }
                    $user->karyawan->delete();
                }
                $user->delete();
            }
            return back()->with('success', 'User terpilih berhasil dihapus.');
        } elseif ($action === 'aktifkan') {
            User::whereIn('id', $ids)->update(['aktif' => 1]);
            return back()->with('success', 'User terpilih berhasil diaktifkan.');
        } elseif ($action === 'nonaktifkan') {
            User::whereIn('id', $ids)->update(['aktif' => 0]);
            return back()->with('success', 'User terpilih berhasil dinonaktifkan.');
        }

        return back()->with('error', 'Aksi tidak dikenali.');
    }
}