<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'city_id',
    ];

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function city () {
        return $this->belongsTo(City::class);
    }
}
