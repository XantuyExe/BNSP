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
    Schema::create('rentals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained();
        $table->foreignId('unit_id')->constrained();
        $table->enum('status', ['PENDING_PAYMENT','ACTIVE','RETURN_REQUESTED','RETURNED','PURCHASED','CANCELLED'])->default('PENDING_PAYMENT');
        $table->timestamp('rental_start')->nullable();
        $table->timestamp('rental_end_plan')->nullable();
        $table->timestamp('rental_end_actual')->nullable();
        $table->timestamp('return_requested_at')->nullable();
        $table->unsignedBigInteger('deposit_required')->default(0);
        $table->unsignedBigInteger('deposit_paid')->default(0);
        $table->unsignedBigInteger('rent_fee_paid')->default(0);
        $table->boolean('eligibility_checked')->default(false);
        $table->text('notes')->nullable();
        $table->timestamps();
        $table->index(['user_id','status']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
