<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'client_service_type_id',
        'branch_id',
        'amount',
        'currency',
        'description',
        'entry_date',
        'status',
    ];

       protected $casts = [
        'entry_date' => 'date',
        'amount' => 'decimal:2', // Cast amount to decimal for consistency
    ];

    /**
     * Get the client that owns the revenue entry.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the service type associated with the revenue entry.
     */
    public function serviceType()
    {
        return $this->belongsTo(ClientServiceType::class, 'client_service_type_id');
    }

    /**
     * Get the branch associated with the revenue entry.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
