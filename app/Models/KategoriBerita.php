<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriBerita extends Model
{
    protected $table = 'kategori_berita';
    protected $fillable = ['kategori_berita'];

    public function berita()
    {
        return $this->hasMany(\App\Models\Berita::class, 'id_kategori_berita');
    }
}
