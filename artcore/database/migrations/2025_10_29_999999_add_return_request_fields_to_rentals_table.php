<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('rentals')) {
            return;
        }

        Schema::table('rentals', function (Blueprint $table) {
            if (!Schema::hasColumn('rentals', 'return_requested_at')) {
                $table->timestamp('return_requested_at')->nullable()->after('rental_end_actual');
            }
        });

        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('PENDING_PAYMENT','ACTIVE','RETURN_REQUESTED','RETURNED','PURCHASED','CANCELLED') NOT NULL DEFAULT 'PENDING_PAYMENT'");
    }

    public function down(): void
    {
        if (!Schema::hasTable('rentals')) {
            return;
        }

        DB::statement("ALTER TABLE rentals MODIFY COLUMN status ENUM('PENDING_PAYMENT','ACTIVE','RETURNED','PURCHASED','CANCELLED') NOT NULL DEFAULT 'PENDING_PAYMENT'");

        Schema::table('rentals', function (Blueprint $table) {
            if (Schema::hasColumn('rentals', 'return_requested_at')) {
                $table->dropColumn('return_requested_at');
            }
        });
    }
};
