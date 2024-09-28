<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'passenger_id',
        'total_price',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
