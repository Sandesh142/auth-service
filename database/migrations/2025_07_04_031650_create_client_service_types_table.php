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
        Schema::create('client_service_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id'); // Explicitly unsignedBigInteger
            $table->string('name'); // e.g., "Pharmacy Sales", "Lab Test: Blood Work"
            $table->text('description')->nullable();
            $table->decimal('default_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true); // Status: 1 (active) or 0 (inactive)
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['client_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_service_types');
    }
};
