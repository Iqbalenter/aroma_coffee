<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->enum('sentimen', ['positif', 'netral', 'negatif'])->nullable()->after('komentar');
            $table->string('kategori_tema', 100)->nullable()->after('sentimen');
        });

        Schema::table('customer_journey', function (Blueprint $table) {
            $table->string('sentimen_dominan', 20)->nullable()->after('rata_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_journey', function (Blueprint $table) {
            $table->dropColumn('sentimen_dominan');
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn(['sentimen', 'kategori_tema']);
        });
    }
};
