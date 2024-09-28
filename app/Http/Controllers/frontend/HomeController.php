<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Terminal;
use Illuminate\Http\Request;

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
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'required',
        ]);

        // Check if schedule information or selected seats are missing from the session
        if (!session('schedule_information') || !session('selected_seats')) {
            return redirect()->back()->with('error', 'No schedule or seats selected. Please try again.');
        }

        // Get the selected seats and schedule information from the session
        $selected_seats = session('selected_seats');
        $schedule_information = session('schedule_information');

        // Calculate total price based on the number of selected seats
        $seat_price = $schedule_information['route']['price']; // Assuming seat price is stored in the session
        $total_price = count($selected_seats) * $seat_price;

        // Create a new booking
        $booking = Booking::create([
            'schedule_id' => $schedule_information['id'], // Use schedule ID from session
            'passenger_id' => auth()->id(), // Assuming passenger is the logged-in user
            'total_price' => $total_price, // Calculated total price
            'status' => 'reserved', // Example status
        ]);

        // Loop through each selected seat and create a seat record with the booking ID and gender
        foreach ($selected_seats as $seat_number) {
            Seat::create([
                'seat_number' => $seat_number,
                'booking_id' => $booking->id,
                'gender' => $request->gender,
            ]);
        }

        // Assuming schedule_information is stored in the session
        $schedule = Schedule::find($schedule_information['id']);

        // Decrease total_seats in the schedule by the number of selected seats
        $schedule->total_seats -= count($selected_seats);
        $schedule->save(); // Save the updated schedule

        // Clear the session after booking
        session()->forget(['schedule_information', 'selected_seats']);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Seats reserved successfully!');
    }
}
