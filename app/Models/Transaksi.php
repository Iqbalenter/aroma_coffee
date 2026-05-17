<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'id_transaksi';

    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan', 'id_operator', 'metode_bayar', 'catatan', 'total_harga', 'tanggal_transaksi',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
        'tanggal_transaksi' => 'datetime',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'id_operator', 'id_operator');
    }

    public function detail()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}
