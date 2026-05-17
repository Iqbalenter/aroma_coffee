<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sessions')) {
            return;
        }

        Schema::table('sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('sessions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->index()->after('id');
            }

            if (Schema::hasColumn('sessions', 'staff_type')) {
                $table->dropColumn('staff_type');
            }

            if (Schema::hasColumn('sessions', 'staff_id')) {
                $table->dropColumn('staff_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sessions')) {
            return;
        }

        Schema::table('sessions', function (Blueprint $table) {
            if (Schema::hasColumn('sessions', 'user_id')) {
                $table->dropColumn('user_id');
            }

            if (! Schema::hasColumn('sessions', 'staff_type')) {
                $table->string('staff_type', 20)->nullable()->after('id');
            }

            if (! Schema::hasColumn('sessions', 'staff_id')) {
                $table->unsignedBigInteger('staff_id')->nullable()->index()->after('staff_type');
            }
        });
    }
};
