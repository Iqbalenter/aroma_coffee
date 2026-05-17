<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Operator extends Authenticatable
{
    protected $table = 'operator';

    protected $primaryKey = 'id_operator';

    public $timestamps = false;

    protected $fillable = ['nama', 'username', 'password', 'level_akses'];

    protected $hidden = ['password'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_operator', 'id_operator');
    }
}
