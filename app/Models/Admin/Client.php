<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\ClientServiceType;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contact_email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'description',
        'status',
        'plan_id',
    ];

    /**
     * Get the users (admins/staff) that belong to the client.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the branches for the client.
     */
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the service types for the client.
     */
    public function serviceTypes()
    {
        return $this->hasMany(ClientServiceType::class);
    }

    /**
     * Get the roles defined by this client.
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Get the revenue entries for the client.
     */
    public function revenueEntries()
    {
        return $this->hasMany(RevenueEntry::class);
    }

    /**
     * Get the medicines belonging to this client.
     */
    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    /**
     * Get the sales made by this client.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
