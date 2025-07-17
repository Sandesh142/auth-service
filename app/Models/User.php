<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\AdminResetPasswordNotification;
use App\Notifications\SuperAdminResetPassword;
use App\Models\Admin\Client; // Assuming Client model is in App\Models\Admin

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'client_id',
        'role_id', // Keep this for the clinic-defined role foreign key
        'role',    // This is for the system-level role (superadmin, admin, staff)
        'status', // Assuming 'status' is a column, if not, use 'is_active'
        'is_active', // Assuming 'is_active' is a column
        'is_two_factor_enabled',
        'two_factor_channel',
        'two_factor_code',
        'two_factor_expires_at',
        'provider_name',
        'provider_id',
        'avatar',
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_expires_at' => 'datetime',
        'is_two_factor_enabled' => 'boolean',
        'is_active' => 'boolean', // Ensure 'is_active' is cast to boolean
    ];

    // --- Relationships ---

    /**
     * Get the client that the user belongs to (for admin/staff users).
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the clinic-defined role that the user belongs to.
     * This assumes a 'role_id' foreign key on the 'users' table.
     */
    public function clinicRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // You had a 'permissions' relationship via hasManyThrough.
    // If you need direct permissions from clinicRole, you can access them via $user->clinicRole->permissions
    // Or, if you want a direct collection of all permissions from the assigned clinic role:
    public function permissions()
    {
        return $this->hasManyThrough(
            Permission::class,
            Role::class,
            'id', // Foreign key on roles table (Role's ID)
            'id', // Foreign key on permissions table (Permission's ID)
            'role_id', // Local key on users table (User's role_id)
            'id' // Local key on roles table (Role's ID for the pivot)
        )->distinct();
    }


    // --- Authorization Helpers ---

    /**
     * Check if the user has a specific system-level role.
     *
     * @param string $role
     * @return bool
     */
    public function hasSystemRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if the user has a specific permission.
     * This checks both system-level permissions (if SuperAdmin) and role-based permissions.
     *
     * @param string $permissionName
     * @return bool
     */
    public function hasPermission(string $permissionName): bool
    {
        // SuperAdmin bypasses all permission checks
        if ($this->hasSystemRole('superadmin')) {
            return true;
        }

        // Check if the user has a clinic-defined role and if that role has the permission
        if ($this->clinicRole) {
            return $this->clinicRole->permissions->contains('name', $permissionName);
        }

        return false;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        if ($this->hasSystemRole('superadmin')) {
            $this->notify(new SuperAdminResetPassword($token));
        } else {
            $this->notify(new AdminResetPasswordNotification($token));
        }
    }
}
