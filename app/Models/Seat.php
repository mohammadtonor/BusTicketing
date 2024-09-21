<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_number', 'booking_id', 'gender',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
