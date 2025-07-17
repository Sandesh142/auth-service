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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); 
            $table->string('name'); 
            $table->string('brand')->nullable(); 
            $table->string('category')->nullable(); 
            $table->string('strength')->nullable(); 
            $table->string('unit')->nullable(); 
            $table->string('barcode')->unique()->nullable(); 
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['1', '0'])->default('1'); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
