<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
   protected $fillable = [
    'name',
    'location',
    'capacity',
    'is_active',
    'is_faulty',
    'fault_note',
];

protected $casts = [
    'is_active' => 'boolean',
    'is_faulty' => 'boolean',
];
    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }
}