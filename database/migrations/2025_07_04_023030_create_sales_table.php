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
        Schema::create('sales', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('client_id'); 
        $table->unsignedBigInteger('user_id'); 
        $table->unsignedBigInteger('branch_id')->nullable(); 
        $table->string('invoice_number')->unique(); 
        $table->decimal('sub_total', 10, 2); 
        $table->decimal('discount_amount', 10, 2)->default(0); 
        $table->decimal('tax_amount', 10, 2)->default(0); 
        $table->decimal('total_amount', 10, 2); 
        $table->decimal('amount_paid', 10, 2)->default(0); 
        $table->decimal('change_due', 10, 2)->default(0);
        $table->string('payment_method')->nullable(); 
        $table->text('notes')->nullable(); 
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
        Schema::dropIfExists('sales');
    }
};
