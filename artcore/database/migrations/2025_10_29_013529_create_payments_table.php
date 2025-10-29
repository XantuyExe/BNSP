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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rental_id')->constrained();
        $table->enum('type', ['RENT_FEE','DEPOSIT','FINAL_PURCHASE','PENALTY']);
        $table->unsignedBigInteger('amount');
        $table->string('method')->nullable(); // VA|CC|EWALLET|CASH
        $table->timestamp('paid_at')->nullable();
        $table->string('ref_code')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
