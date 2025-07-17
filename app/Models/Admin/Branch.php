<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'address',
        'city',
        'state',
        'zip_code',
        'phone',
        'contact_person',
        'status',
    ];

    /**
     * Get the client that owns the branch.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the revenue entries associated with the branch.
     */
    public function revenueEntries()
    {
        return $this->hasMany(RevenueEntry::class);
    }

    /**
     * Get the sales associated with the branch.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
