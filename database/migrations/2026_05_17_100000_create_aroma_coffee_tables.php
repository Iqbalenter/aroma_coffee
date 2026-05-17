<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id('id_admin');
            $table->string('nama', 100);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('level_akses', 20);
        });

        Schema::create('operator', function (Blueprint $table) {
            $table->id('id_operator');
            $table->string('nama', 100);
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('level_akses', 20);
        });

        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->string('nama', 100);
            $table->string('email', 100)->nullable()->unique();
            $table->string('nomor_hp', 15)->nullable();
            $table->text('alamat')->nullable();
            $table->enum('status', ['baru', 'aktif', 'potensial_loyal', 'loyal', 'tidak_aktif'])->default('baru');
            $table->dateTime('tanggal_daftar')->useCurrent();
        });

        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk', 100);
            $table->string('kategori', 50)->nullable();
            $table->decimal('harga', 10, 2);
            $table->text('deskripsi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->enum('status', ['tersedia', 'habis'])->default('tersedia');
        });

        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_operator');
            $table->string('metode_bayar', 50)->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 10, 2);
            $table->dateTime('tanggal_transaksi')->useCurrent();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id_operator')->references('id_operator')->on('operator')
                ->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_produk');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('subtotal', 10, 2);

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_produk')->references('id_produk')->on('produk')
                ->cascadeOnUpdate()->restrictOnDelete();
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id('id_feedback');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_produk');
            $table->string('kategori', 100)->nullable();
            $table->enum('tahap_journey', [
                'awareness', 'consideration', 'purchase', 'experience', 'retention', 'loyalty',
            ]);
            $table->unsignedTinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->enum('status', ['baru', 'ditinjau', 'selesai'])->default('baru');
            $table->dateTime('tanggal_feedback')->useCurrent();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_produk')->references('id_produk')->on('produk')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('customer_journey', function (Blueprint $table) {
            $table->id('id_journey');
            $table->enum('tahapan', [
                'awareness', 'consideration', 'purchase', 'experience', 'retention', 'loyalty',
            ]);
            $table->integer('jumlah_feedback')->default(0);
            $table->decimal('rata_rating', 3, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_journey');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('operator');
        Schema::dropIfExists('admin');
    }
};
