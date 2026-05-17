# Aroma Coffee Bland — Customer Journey Mapping (Laravel)

Aplikasi **full Laravel** untuk sistem pemetaan perjalanan pelanggan (CJM) kafe Aroma Coffee Bland.

## Fitur

- Login **Admin** dan **Operator** (tabel terpisah)
- CRUD Pelanggan, Produk (admin), Transaksi (operator), Feedback
- Customer Journey Mapping dengan agregasi tabel `customer_journey`
- Segmentasi pelanggan otomatis
- Dashboard & laporan dengan grafik

## Persyaratan

- PHP 8.2+
- Composer
- MySQL / MariaDB
- Extension PHP: `pdo_mysql`, `mbstring`, `openssl`

## Instalasi

1. Buat database (sesuai skema Anda):

```sql
CREATE DATABASE IF NOT EXISTS db_aroma_coffee_bland;
```

2. Salin dan sesuaikan `.env` (sudah diset untuk MySQL):

```
DB_DATABASE=db_aroma_coffee_bland
DB_USERNAME=root
DB_PASSWORD=
```

3. Jalankan migrasi & seeder:

```bash
cd laravel-tmp
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

4. Buka http://127.0.0.1:8000

## Akun demo

| Role     | Username  | Password  |
|----------|-----------|-----------|
| Admin    | `admin`   | `password` |
| Operator | `operator`| `password` |

## Struktur database

Sesuai skema `db_aroma_coffee_bland`: `admin`, `operator`, `pelanggan`, `produk`, `transaksi`, `detail_transaksi`, `feedback`, `customer_journey`.

## Catatan

- Kode React/Vite lama ada di folder root proyek (versi sebelumnya memakai localStorage).
- Aplikasi Laravel aktif berada di folder **`laravel-tmp/`**.
