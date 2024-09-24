<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Terminal;
use Illuminate\Http\Request;

class HomeController extends Controller
{
     // Display a listing of the terminals
     public function index() {
        return View('frontend.index');
     }

     public function search() {

      $terminals = Terminal::all(); // Assuming you have a Terminal model
      return View('frontend.search-schedules', compact('terminals'));
     }

     public function searchSchedules(Request $request)
    {
        $request->validate([
            'origin_terminal_id' => 'required|exists:terminals,id',
            'destination_terminal_id' => 'required|exists:terminals,id',
            'departure_date' => 'required|date',
        ]);

        // Find routes that match the given origin and destination
        $routes = Route::where('origin_terminal_id', $request->origin_terminal_id)
            ->where('destination_terminal_id', $request->destination_terminal_id)
            ->get();

        // Get schedules for the found routes
        $schedules = Schedule::with(['bus', 'route', 'route.originTerminal', 'route.originTerminal.city','route.destinationTerminal', 'route.destinationTerminal.city'])
            ->whereIn('route_id', $routes->pluck('id'))
            ->whereDate('departure_time', '=', $request->departure_date)
            ->get();

            return response()->json(['schedules' => $schedules]);
    }

    public function searchResult () {

    }
}
