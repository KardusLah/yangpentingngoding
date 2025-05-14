<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table = 'reservasi';

    protected $fillable = [
        'id_pelanggan',
        'id_paket',
        'tgl_reservasi_wisata',
        'harga',
        'jumlah_peserta',
        'diskon',
        'nilai_diskon',
        'total_bayar',
        'file_bukti_tf',
        'status_reservasi_wisata',
        'tgl_mulai',
        'tgl_akhir',
        'lama_reservasi',
        'no_rekening', // <--- tambahkan ini
        'bank_id',     // <--- tambahkan jika ingin relasi bank
        // 'midtrans_order_id',   // <--- tambahkan ini
        // 'payment_url',    
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function paket()
    {
        return $this->belongsTo(PaketWisata::class, 'id_paket');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

}


