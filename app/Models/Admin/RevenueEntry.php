<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RevenueEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'client_service_type_id',
        'branch_id',
        'user_id',
        'amount',
        'currency',
        'description',
        'entry_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'entry_date' => 'date',
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

    /**
     * Get the user who recorded the revenue entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
