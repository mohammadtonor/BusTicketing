<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Show schedule by reserved seats and passenger details with admin controls
    public function showScheduleReservedPassengers($scheduleId)
    {
        // Fetch the schedule with bookings and related passenger details
        $schedule = Schedule::with(['bookings.seats', 'bookings.user'])
            ->where('id', $scheduleId)
            ->firstOrFail();

        // Get all passengers who have reserved seats
        $allPassengers = User::whereHas('bookings', function ($query) use ($scheduleId) {
            $query->where('schedule_id', $scheduleId);
        })->get();

        $schedule_id = $schedule->id;
        $seats = Seat::whereHas('booking', function ($query) use ($schedule_id) {
            $query->where('schedule_id', $schedule_id);
        })->get(['seat_number', 'gender'])
            ->keyBy('seat_number'); // Key by seat_number for quick lookup in the view

        //dd($schedule, $allPassengers);
        return view('admin.bookings.show-reserved-passenger', compact('schedule', 'allPassengers', 'seats'));
    }

    // Cancel a specific seat for a passenger
    public function cancelSeat(Request $request, $seatId)
    {
        $seat = Seat::findOrFail($seatId);
        $seat->booking->schedule->total_seats += 1;
        $seat->booking->schedule->save();
        $seat->delete(); // Delete the seat

        return redirect()->back()->with('success', 'Seat canceled successfully.');
    }

    // Book a seat for a passenger
    public function bookSeat(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'gender' => 'required',
            "total_price" => 'required',
            'seat_number' => 'required|integer',
        ]);
        //dd($request->all());
        // Find or create the user (passenger)
        $passenger = User::firstOrCreate(
            ['email' => $request->email],
            [
                'password' => "password",
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'gender' => $request->gender
            ],
        );

        // Create a new booking and assign the seat
        $booking = Booking::create([
            'schedule_id' => $request->schedule_id,
            'passenger_id' => $passenger->id,
            'total_price' => $request->total_price,
            'status' => 'reserved'
        ]);

        $booking->schedule->total_seats -= 1;
        $booking->schedule->save();

        // Create the seat
        Seat::create([
            'seat_number' => $request->seat_number,
            'booking_id' => $booking->id,
            'gender' => $request->gender,
            "national_num" => $request->national_num,
            "full_name" => $request->national_num
        ]);

        return redirect()->back()->with('success', 'Seat booked successfully for the passenger.');
    }
}
