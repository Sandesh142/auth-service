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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('medicine_id'); 
            $table->string('batch_number')->index(); 
            $table->date('manufacture_date')->nullable(); 
            $table->date('expiry_date'); 
            $table->decimal('cost_price', 10, 2); 
            $table->integer('initial_quantity');
            $table->integer('current_quantity'); 
            $table->string('supplier')->nullable(); 
            $table->enum('status', ['1', '0'])->default('1'); 
            $table->softDeletes();
            $table->unique(['client_id', 'medicine_id', 'batch_number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
