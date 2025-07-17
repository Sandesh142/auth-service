<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Permission; // Assuming Permission model is in App\Models\Admin
use App\Models\Admin\Client;     // Assuming Client model is in App\Models\Admin

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'description',
        'guard_name',
        'status',
        'is_two_factor_enabled',
        'two_factor_channel',
    ];

    protected $casts = [
        'is_two_factor_enabled' => 'boolean',
    ];

    /**
     * Get the users that have this role.
     * This assumes a 'role_id' foreign key on the 'users' table.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}
