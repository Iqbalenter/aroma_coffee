<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerJourney extends Model
{
    protected $table = 'customer_journey';

    protected $primaryKey = 'id_journey';

    public $timestamps = false;

    protected $fillable = ['tahapan', 'jumlah_feedback', 'rata_rating', 'sentimen_dominan'];

    protected $casts = [
        'rata_rating' => 'decimal:2',
    ];

    public const TAHAPAN = [
        'awareness' => ['title' => 'Awareness', 'desc' => 'Menyadari brand'],
        'consideration' => ['title' => 'Consideration', 'desc' => 'Mempertimbangkan beli'],
        'purchase' => ['title' => 'Purchase', 'desc' => 'Melakukan transaksi'],
        'experience' => ['title' => 'Experience', 'desc' => 'Menikmati produk/layanan'],
        'retention' => ['title' => 'Retention', 'desc' => 'Pembelian kembali'],
        'loyalty' => ['title' => 'Loyalty', 'desc' => 'Merekomendasikan ke pihak lain'],
    ];
}
