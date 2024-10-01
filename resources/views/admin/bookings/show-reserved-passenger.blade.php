@extends('admin.layouts.master')
@section('title')
    Schedule Reserved Passengers
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <!-- Left Section: Admin Controls for Seat Management -->
            <div class="col-md-6">
                <div class=" mb-4">
                    <div class="card-body">
                        <h4>Schedule: {{ $schedule->route->originTerminal->name }} to
                            {{ $schedule->route->destinationTerminal->name }}</h4>
                        <p>Departure Time: {{ $schedule->departure_time }}</p>
                        <p>Total Seats: {{ $schedule->total_seats }}</p>

                        <h5>Reserved Seats</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Seat Number</th>
                                    <th>Passenger Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedule->bookings as $booking)
                                    @foreach ($booking->seats as $seat)
                                        <tr>
                                            <td>{{ $seat->seat_number }}</td>
                                            <td>{{ $seat->full_name }}</td>
                                            <td>
                                                <!-- Cancel Seat -->
                                                <form action="{{ route('admin.cancel-seat', $seat->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Trigger Modal Button -->
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal"
                            data-bs-target="#bookSeatModal">
                            Book Seat for Passenger
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Section: Display Available and Reserved Seats -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Seats Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            @php $seatNumber = 1; @endphp
                            @while ($seatNumber <= $schedule->total_seats)
                                <div class="row">
                                    <!-- If total seats <= 30, arrange seats in columns 1, 3, and 4 -->
                                    @if ($schedule->total_seats <= 30)
                                        <div class="col-3">
                                            @if ($seatNumber <= $schedule->total_seats)
                                                @php
                                                    $seat = $seats->get($seatNumber);
                                                    $isBooked = $seat && !is_null($seat->gender);
                                                    $seatGender = $seat ? ucfirst($seat->gender) : null;
                                                @endphp

                                                @if ($isBooked)
                                                    <!-- Booked seat -->
                                                    <button type="button"
                                                        class="btn {{ $seatGender === 'Male' ? 'btn-primary' : 'btn-danger' }} w-100 mb-2"
                                                        disabled>
                                                        {{ $seatNumber }}
                                                    </button>
                                                @else
                                                    <!-- Available seat -->
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#bookSeatModal" class="btn btn-success w-100 mb-2"
                                                        data-seat-number="{{ $seatNumber }}">
                                                        {{ $seatNumber }}
                                                    </button>
                                                @endif
                                            @endif
                                            @php $seatNumber++; @endphp
                                        </div>

                                        <!-- Empty Column (col-2) -->
                                        <div class="col-3"></div>

                                        <div class="col-3">
                                            @if ($seatNumber <= $schedule->total_seats)
                                                @php
                                                    $seat = $seats->get($seatNumber);
                                                    $isBooked = $seat && !is_null($seat->gender);
                                                    $seatGender = $seat ? ucfirst($seat->gender) : null;
                                                @endphp

                                                @if ($isBooked)
                                                    <button type="button"
                                                        class="btn {{ $seatGender === 'Male' ? 'btn-primary' : 'btn-danger' }} w-100 mb-2"
                                                        disabled>
                                                        {{ $seatNumber }}

                                                    </button>
                                                @else
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#bookSeatModal" class="btn btn-success w-100 mb-2"
                                                        data-seat-number="{{ $seatNumber }}">
                                                        {{ $seatNumber }}
                                                    </button>
                                                @endif
                                            @endif
                                            @php $seatNumber++; @endphp
                                        </div>

                                        <div class="col-3">
                                            @if ($seatNumber <= $schedule->total_seats)
                                                @php
                                                    $seat = $seats->get($seatNumber);
                                                    $isBooked = $seat && !is_null($seat->gender);
                                                    $seatGender = $seat ? ucfirst($seat->gender) : null;
                                                @endphp

                                                @if ($isBooked)
                                                    <button type="button"
                                                        class="btn {{ $seatGender === 'Male' ? 'btn-primary' : 'btn-danger' }} w-100 mb-2"
                                                        disabled>
                                                        {{ $seatNumber }}
                                                    </button>
                                                @else
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#bookSeatModal" class="btn btn-success w-100 mb-2"
                                                        data-seat-number="{{ $seatNumber }}">
                                                        {{ $seatNumber }}
                                                    </button>
                                                @endif
                                            @endif
                                            @php $seatNumber++; @endphp
                                        </div>
                                    @else
                                        <!-- If total seats > 30, use a 4-column layout -->
                                        @for ($i = 0; $i < 4 && $seatNumber <= $schedule->total_seats; $i++, $seatNumber++)
                                            <div class="col-3">
                                                @php
                                                    $seat = $seats->get($seatNumber);
                                                    $isBooked = $seat && !is_null($seat->gender);
                                                    $seatGender = $seat ? ucfirst($seat->gender) : null;
                                                @endphp

                                                @if ($isBooked)
                                                    <button type="button"
                                                        class="btn {{ $seatGender === 'male' ? 'btn-primary' : 'btn-danger' }} w-100 mb-2"
                                                        disabled>
                                                        {{ $seatNumber }}
                                                    </button>
                                                @else
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#bookSeatModal" class="btn btn-success w-100 mb-2"
                                                        data-seat-number="{{ $seatNumber }}">
                                                        {{ $seatNumber }}
                                                    </button>
                                                @endif
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            @endwhile
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Booking a Seat -->
    <div class="modal fade" id="bookSeatModal" tabindex="-1" aria-labelledby="bookSeatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookSeatModalLabel">Book Seat for Passenger</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.book-seat') }}" method="POST">
                        @csrf
                        <input type="hidden" name="total_price" value="{{ $schedule->route->price }}">
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                        <div class="form-group mb-3">
                            <label for="first_name">Passenger First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="last_name">Passenger Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">Passenger Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="seat_number">Seat Number</label>
                            <input type="number" name="seat_number" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="" disabled>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Book Seat</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // When the modal is about to be shown
            $('#bookSeatModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var seatNumber = button.data('seat-number'); // Extract info from data-* attributes

                // Update the modal's input field with the seat number
                var modal = $(this);
                modal.find('.modal-body input[name="seat_number"]').val(seatNumber);
            });
        });
    </script>
@endpush
