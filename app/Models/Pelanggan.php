<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $primaryKey = 'id_pelanggan';

    public $timestamps = false;

    protected $fillable = ['nama', 'email', 'nomor_hp', 'alamat', 'status', 'tanggal_daftar'];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'baru' => 'Baru',
            'aktif' => 'Aktif',
            'potensial_loyal' => 'Potensial Loyal',
            'loyal' => 'Loyal',
            'tidak_aktif' => 'Tidak Aktif',
            default => ucfirst($this->status),
        };
    }
}
