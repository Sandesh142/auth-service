<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status',
        'is_two_factor_enabled',
        'two_factor_channel',
    ];

    protected $casts = [
        'is_two_factor_enabled' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
