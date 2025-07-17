<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

        protected $fillable = [
        'client_id',
        'name',
        'brand',
        'category',
        'strength',
        'unit',
        'barcode',
        'description',
        'is_active',
    ];

        public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function totalStock()
    {
        return $this->batches()->sum('current_quantity');
    }
}
