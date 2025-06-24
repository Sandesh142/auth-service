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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->after('id');
            $table->unsignedBigInteger('role_id')->after('client_id');
            $table->enum('status', [0, 1, 2, 3])->default(1)->after('password');
            $table->boolean('is_two_factor_enabled')->default(false)->after('status');
            $table->enum('two_factor_channel', ['email', 'mobile'])->nullable()->after('is_two_factor_enabled');
            $table->string('two_factor_code')->nullable()->after('two_factor_channel');
            $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');

            $table->string('provider_name')->nullable()->after('two_factor_expires_at');
            $table->string('provider_id')->nullable()->after('provider_name');
            $table->string('avatar')->nullable()->after('provider_id');

            $table->string('first_name')->nullable()->after('avatar');
            $table->string('last_name')->nullable()->after('first_name');

            $table->softDeletes()->after('two_factor_expires_at'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'client_id', 
                'role_id', 
                'status', 
                'deleted_at', 
                'is_two_factor_enabled', 
                'two_factor_channel', 
                'two_factor_code', 
                'two_factor_expires_at',                 
                'provider_name',
                'provider_id',
                'avatar',
                'first_name',
                'last_name',
            ]);
        });
    }
};
