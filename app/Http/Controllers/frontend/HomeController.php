<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Terminal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    // Display a listing of the terminals
    public function index()
    {
        return View('frontend.index');
    }

    public function search()
    {

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
        $schedules = Schedule::with([
            'bus',
            'route',
            'route.originTerminal',
            'route.originTerminal.city',
            'route.destinationTerminal',
            'route.destinationTerminal.city'
        ])
            ->whereIn('route_id', $routes->pluck('id'))
            ->whereDate('departure_time', '=', $request->departure_date)
            ->get();

        return response()->json(['schedules' => $schedules]);
    }

    public function showSchedule(Schedule $schedule)
    {
        // Get the schedule, bus, and route information
        $scheduleWithRoute = Schedule::with([
            'bus',
            'route',
            'route.originTerminal',
            'route.originTerminal.city',
            'route.destinationTerminal',
            'route.destinationTerminal.city'
        ])->find($schedule->id);

        $schedule_id = $schedule->id;
        // We're checking the seats from bookings that belong to this schedule
        $seats = Seat::whereHas('booking', function ($query) use ($schedule_id) {
            $query->where('schedule_id', $schedule_id);
        })->get(['seat_number', 'gender'])
            ->keyBy('seat_number'); // Key by seat_number for quick lookup in the view

        return view('frontend.show-schedules', compact('schedule', 'seats'));
    }

    public function storeSeats(Request $request)
    {
        // Validate that selected_seats is an array
        $request->validate([
            'selected_seats' => 'required|array|min:1',
        ]);

        // Store the selected seats in the session
        $scheduleWithRoute = Schedule::with([
            'bus',
            'route',
            'route.originTerminal',
            'route.originTerminal.city',
            'route.destinationTerminal',
            'route.destinationTerminal.city'
        ])->find($request->schedule_id)->toArray();

        session(['selected_seats' => $request->selected_seats]);
        session(['schedule_information' => $scheduleWithRoute]);

        return redirect()->route('booking.information', $request->schedule_id)->with('success', 'Route created successfully.');
    }

    public function infoBooking(Schedule $schedule)
    {
        return view('frontend.booking-Schedule', compact('schedule'));
    }

    public function confirmBooking(Request $request)
    {
        // Retrieve schedule information from the session
        $scheduleInformation = session('schedule_information');
        $selectedSeats = session('selected_seats');

        // Ensure that schedule information and selected seats exist
        if (!$scheduleInformation || !$selectedSeats) {
            return redirect()->back()->withErrors('No schedule or seat information found.');
        }

        // For the first passenger (authenticated user), create the booking with their info
        $firstPassenger = $request->passengers[0];

        $booking = Booking::create([
            'passenger_id' => Auth::id(),
            'phone_number' => $firstPassenger['phone'],
            'schedule_id' => $scheduleInformation['id'],
            'total_price' => $scheduleInformation['route']['price'] * count($selectedSeats),
            'status' => 'booked',
        ]);

        // Attach the first seat to the booking
        Seat::create([
            'seat_number' => $firstPassenger['seat_number'],
            'booking_id' => $booking->id,
            "full_name" =>  $firstPassenger['name'],
            'national_num' => $firstPassenger['national_num'],
            'gender' => $firstPassenger['gender'],
        ]);

        // Handle subsequent passengers
        foreach ($request->passengers as $index => $passenger) {
            if ($index > 0) {
                // Create a new booking for each subsequent passenger
                Seat::create([
                    'seat_number' => $passenger['seat_number'],
                    'booking_id' => $booking->id, // Use the same booking ID
                    'gender' => $passenger['gender'],
                    'national_num' => $passenger['national_num'],
                    "full_name" =>  $firstPassenger['name'],
                ]);
            }
        }


        // Assuming schedule_information is stored in the session
        $schedule = Schedule::find($scheduleInformation['id']);

        // Decrease total_seats in the schedule by the number of selected seats
        $schedule->total_seats -= count($selectedSeats);
        $schedule->save(); // Save the updated schedule

        // Clear the session after booking
        session()->forget(['schedule_information', 'selected_seats']);

        // Redirect back with a success message
        return redirect()->route('booked-schedules')->with('success', 'Seats reserved successfully!');
    }
}
