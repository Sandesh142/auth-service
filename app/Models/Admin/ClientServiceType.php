<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientServiceType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'description',
        'default_price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean', // Cast 1/0 to boolean
        'default_price' => 'decimal:2',
    ];

    /**
     * Get the client that owns the service type.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the revenue entries associated with this service type.
     */
    public function revenueEntries()
    {
        return $this->hasMany(RevenueEntry::class, 'client_service_type_id');
    }
}
