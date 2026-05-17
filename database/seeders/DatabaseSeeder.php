<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\CustomerJourney;
use App\Models\Operator;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'level_akses' => 'admin',
        ]);

        Operator::create([
            'nama' => 'Operator Kasir',
            'username' => 'operator',
            'password' => Hash::make('password'),
            'level_akses' => 'operator',
        ]);

        foreach (array_keys(CustomerJourney::TAHAPAN) as $tahapan) {
            CustomerJourney::create([
                'tahapan' => $tahapan,
                'jumlah_feedback' => 0,
                'rata_rating' => 0,
            ]);
        }

        Pelanggan::insert([
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@test.com',
                'nomor_hp' => '081234567890',
                'alamat' => 'Medan',
                'status' => 'loyal',
                'tanggal_daftar' => '2023-01-10 00:00:00',
            ],
            [
                'nama' => 'Siti Aminah',
                'email' => 'siti@test.com',
                'nomor_hp' => '081987654321',
                'alamat' => 'Medan',
                'status' => 'aktif',
                'tanggal_daftar' => '2024-02-15 00:00:00',
            ],
        ]);

        Produk::insert([
            [
                'nama_produk' => 'Caramel Macchiato',
                'kategori' => 'Coffee',
                'harga' => 25000,
                'deskripsi' => 'Kopi dengan caramel',
                'status' => 'tersedia',
            ],
            [
                'nama_produk' => 'Hazelnut Latte',
                'kategori' => 'Coffee',
                'harga' => 23000,
                'deskripsi' => 'Latte dengan hazelnut',
                'status' => 'tersedia',
            ],
        ]);
    }
}
