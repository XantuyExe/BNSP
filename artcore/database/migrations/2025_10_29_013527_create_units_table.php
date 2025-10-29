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
    Schema::create('units', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained();
        $table->string('code')->unique();        // 1988-LUKISAN-A
        $table->string('name');                  // 1988 Lukisan A
        $table->enum('vintage', ['60s','70s','80s','90s']);
        $table->unsignedBigInteger('sale_price');      // dalam rupiah
        $table->unsignedBigInteger('rent_price_5d');   // biaya sewa paket 5 hari
        $table->boolean('is_available')->default(true);
        $table->json('images')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
        $table->index(['category_id','vintage','is_available']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
