<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'medicine_id',
        'batch_number',
        'manufacture_date',
        'expiry_date',
        'cost_price',
        'initial_quantity',
        'current_quantity', 
        'supplier',
    ];

    protected $casts = [
        'manufacture_date' => 'date',
        'expiry_date' => 'date',
        'cost_price' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
