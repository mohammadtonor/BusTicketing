<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_number', 'bus_name', 'total_seats',
    ];

    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
