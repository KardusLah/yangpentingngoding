<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', // Tambahkan kolom ini jika diperlukan di migration.
        'email',
        'password',
        'no_hp',
        'level',
        'aktif',
    ];

    protected static function boot()
     {
         parent::boot();
     
         static::creating(function ($user) {
             if (!$user->level) {
                 $user->level = 'pelanggan';
             }
         });
     }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute($value)
    {
        // Jika sudah ada di kolom users, pakai itu
        if ($value) return $value;

        // Jika level pelanggan, ambil dari relasi pelanggan
        if ($this->level === 'pelanggan' && $this->pelanggan) {
            return $this->pelanggan->nama_lengkap;
        }

        // Jika level karyawan, ambil dari relasi karyawan
        if ($this->level === 'admin' || $this->level === 'bendahara' || $this->level === 'pemilik') {
            if ($this->karyawan) {
                return $this->karyawan->nama_karyawan;
            }
        }

        return null;
    }

    public function pelanggan(): HasOne
    {
        return $this->hasOne(Pelanggan::class, 'id_user', 'id');
    }

    public function karyawan(): HasOne
    {
        return $this->hasOne(Karyawan::class, 'id_user', 'id');
    }
}
