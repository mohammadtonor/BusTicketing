<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_terminal_id', 'destination_terminal_id', 'distance', 'price', 'duration'
    ];

     // Start Terminal of the route
     public function originTerminal()
     {
         return $this->belongsTo(Terminal::class, 'origin_terminal_id');
     }
 
     // End Terminal of the route
     public function destinationTerminal()
     {
         return $this->belongsTo(Terminal::class, 'destination_terminal_id');
     }
 
     // One Route can have many Schedules
     public function schedules()
     {
         return $this->hasMany(Schedule::class);
     }

}
