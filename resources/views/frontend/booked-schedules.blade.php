@extends('frontend.layouts.master')
@section('title')
    My Booked Schedules
@endsection

@section('content')
    <div class="container">
        <h2>Booked Schedules</h2>

        @foreach ($bookings as $booking)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title d-flex">Schedule: {{ $booking->schedule->route->originTerminal->name }} to
                        {{ $booking->schedule->route->destinationTerminal->name }}</h5>
                    <p>Departure: {{ $booking->schedule->departure_time }}</p>
                    <p>Total Price: ${{ $booking->total_price }}</p>

                    <h6 class="">Seats:</h6>
                    <ul class="list-group list-group-flush d-flex">
                        @foreach ($booking->seats as $seat)
                            <li class="list-group-item ">
                                Seat {{ $seat->seat_number }}
                                <button class="btn btn-danger btn-sm float-right cancel-seat"
                                    data-seat-id="{{ $seat->id }}">Cancel</button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="d-flex justify-content-end">

                        <!-- Cancel all seats for this booking -->
                        <button class="btn btn-warning mt-3 d-flex justify-content-end cancel-all-seats"
                            data-booking-id="{{ $booking->id }}">
                            Cancel All Seats
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal for confirmation -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancel Seat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this seat?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelBtn">Cancel Seat</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for canceling all seats -->
    <div class="modal fade" id="cancelAllModal" tabindex="-1" aria-labelledby="cancelAllModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelAllModalLabel">Cancel All Seats</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel all seats for this booking?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelAllBtn">Cancel All Seats</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var cancelSeatId, cancelBookingId;

            // Trigger modal for canceling specific seat
            $('.cancel-seat').on('click', function() {
                cancelSeatId = $(this).data('seat-id');
                $('#cancelModal').modal('show');
            });

            // Confirm cancel seat
            $('#confirmCancelBtn').on('click', function() {
                $.ajax({
                    url: '/user/cancel-seat/' + cancelSeatId,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(error) {
                        alert('Error canceling seat');
                    }
                });
            });

            // Trigger modal for canceling all seats
            $('.cancel-all-seats').on('click', function() {
                cancelBookingId = $(this).data('booking-id');
                $('#cancelAllModal').modal('show');
            });

            // Confirm cancel all seats
            $('#confirmCancelAllBtn').on('click', function() {
                $.ajax({
                    url: '/user/cancel-all-seats/' + cancelBookingId,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(error) {
                        alert('Error canceling all seats');
                    }
                });
            });
        });
    </script>
@endpush
