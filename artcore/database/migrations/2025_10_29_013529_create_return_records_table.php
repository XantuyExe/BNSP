<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('return_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rental_id')->unique()->constrained();
        $table->foreignId('admin_id')->constrained('users');
        $table->timestamp('return_checked_at')->nullable();
        $table->unsignedBigInteger('cleaning_fee')->default(0);
        $table->unsignedBigInteger('damage_fee')->default(0);
        $table->unsignedBigInteger('deposit_refund')->default(0);
        $table->text('condition_note')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_records');
    }
};
