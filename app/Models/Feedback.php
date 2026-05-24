<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $primaryKey = 'id_feedback';

    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan', 'id_produk', 'kategori', 'tahap_journey', 'rating', 'komentar', 'sentimen', 'kategori_tema', 'status', 'tanggal_feedback',
    ];

    protected $casts = [
        'tanggal_feedback' => 'datetime',
        'rating' => 'integer',
    ];

    public const TAHAPAN = [
        'awareness' => 'Awareness',
        'consideration' => 'Consideration',
        'purchase' => 'Purchase',
        'experience' => 'Experience',
        'retention' => 'Retention',
        'loyalty' => 'Loyalty',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function isPainPoint(): bool
    {
        return $this->rating <= 2;
    }

    public function getTahapLabelAttribute(): string
    {
        return self::TAHAPAN[$this->tahap_journey] ?? ucfirst($this->tahap_journey);
    }
}
