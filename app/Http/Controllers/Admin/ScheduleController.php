<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the schedules.
     */
    public function index()
    {
        $schedules = Schedule::with(['bus', 'route.originTerminal', 'route.destinationTerminal'])->get();
        $buses = Bus::all();
        $routes = Route::with(['originTerminal', 'destinationTerminal'])->get();

        return view('admin.schedules.index', compact('schedules', 'buses', 'routes'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'departure_time' => 'required',
            'total_seats' => 'required|integer|min:1',
        ]);

        // Parse the start and end date from the request
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $departureTime = $request->departure_time;

        // Iterate over each date in the date range
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Combine the date with the time for each schedule
            $departureDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $departureTime);

            // Create a schedule for each day in the range
            Schedule::create([
                'bus_id' => $request->bus_id,
                'route_id' => $request->route_id,
                'departure_time' => $departureDateTime,
                'total_seats' => $request->total_seats,
            ]);
        }

        // Redirect back to the schedules list with a success message
        return redirect()->route('schedules.index')->with('success', 'Schedules created successfully.');
    }


    /**
     * Update the specified schedule in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date',
            'total_seats' => 'required|integer|min:1',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'bus_id' => $request->bus_id,
            'route_id' => $request->route_id,
            'departure_time' => Carbon::parse($request->departure_time),
            'total_seats' => $request->total_seats,
        ]);

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified schedule from storage.
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully.');
    }
}
