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
    Schema::create('penalties', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rental_id')->constrained();
        $table->enum('kind', ['CLEANING','DAMAGE','LATE']);
        $table->unsignedBigInteger('amount');
        $table->text('reason')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');
    }
};
