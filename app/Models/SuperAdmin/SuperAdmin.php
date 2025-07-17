<?php

namespace App\Models\SuperAdmin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\SuperAdminResetPassword; // Assuming this notification exists

class SuperAdmin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'super_admins'; // Make sure your superadmin table is named 'super_admins'

    protected $fillable = [
        'name',
        'email',
        'password',
        // 'role_id', // Remove if not used to differentiate superadmin types
        'status',
        'provider_name',
        'provider_id',
        'avatar',
        'first_name',
        'last_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // Add other casts for new columns if they exist in super_admins table
        // 'status' => 'string', // or enum
        // 'is_two_factor_enabled' => 'boolean',
        // 'two_factor_expires_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SuperAdminResetPassword($token));
    }

    // Removed the hasSystemRole method from here, as this model IS the SuperAdmin.
    // Logic for SuperAdmin permissions will be handled by checking Auth::guard('superadmin')->check()
    // or Auth::guard('superadmin')->user() directly in Blade or controllers.
}
