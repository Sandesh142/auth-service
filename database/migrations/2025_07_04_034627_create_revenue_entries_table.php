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
        Schema::create('revenue_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('client_service_type_id');
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->unsignedBigInteger('user_id')->nullable();

            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR'); 
            $table->text('description')->nullable();
            $table->date('entry_date');
            $table->string('status')->default('completed'); 

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_entries');
    }
};
