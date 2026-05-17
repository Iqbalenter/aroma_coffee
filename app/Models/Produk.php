<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $primaryKey = 'id_produk';

    public $timestamps = false;

    protected $fillable = ['nama_produk', 'kategori', 'harga', 'deskripsi', 'gambar', 'status'];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_produk', 'id_produk');
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }
}
