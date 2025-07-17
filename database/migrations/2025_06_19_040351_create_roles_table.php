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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable(); // New: Foreign key to clients table, nullable for system-wide roles
            $table->string('name')->unique();
            $table->text('description')->nullable(); // Changed from string to text for potentially longer descriptions
            $table->enum('status', ['active', 'inactive'])->default('active'); // Changed from [0,1,2,3] to 'active','inactive' for clarity and consistency
            $table->boolean('is_two_factor_enabled')->default(false);
            $table->enum('two_factor_channel', ['email', 'mobile'])->nullable();
            $table->string('guard_name')->default('web'); // New: For Laravel permissions package compatibility

            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
