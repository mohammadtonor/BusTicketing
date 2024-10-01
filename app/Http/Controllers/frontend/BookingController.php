<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Show all booked schedules by the user
    public function bookedSchedules()
    {
        $user = Auth::user();
        // Fetch all bookings of the logged-in user along with their seats and schedules
        $bookings = Booking::with(['schedule.route.originTerminal', 'schedule.route.destinationTerminal', 'seats'])
            ->where('passenger_id', $user->id)
            ->get();

        return view('frontend.booked-schedules', compact('bookings'));
    }

    public function cancelSeat(Request $request, $seatId)
    {
        $seat = Seat::findOrFail($seatId);
        $booking = $seat->booking;

        if ($booking->passenger_id == Auth::id()) {
            // Determine the price per seat (Assuming you have a field for this in the schedule or booking)
            $schedule = $booking->schedule;
            $pricePerSeat = $schedule->route->price; // Assuming the price per seat is stored in the schedule

            // Decrease the total price by the price of one seat
            $booking->total_price -= $pricePerSeat;

            // Save the updated booking
            $booking->save();

            // Delete the seat record
            $seat->delete();

            return response()->json(['message' => 'Seat canceled successfully, and price updated']);
        }

        return response()->json(['error' => 'Unauthorized action'], 403);
    }

    // Cancel all seats for a specific booking
    public function cancelAllSeats(Request $request, $bookingId)
    {
        $booking = Booking::with('seats')->findOrFail($bookingId);

        if ($booking->passenger_id == Auth::id()) {
            $booking->seats()->delete(); // Delete all seats for the booking
            $booking->delete(); // Optionally delete the booking itself

            $booking->schedule->total_seats += count($booking->seats);
            return response()->json(['message' => 'All seats canceled successfully']);
        }

        return response()->json(['error' => 'Unauthorized action'], 403);
    }
}
