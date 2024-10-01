@extends('frontend.layouts.master')
@section('title')
    Search - Tickets
@endsection
@section('content')
    <div class="container">
        <div class="row g-3">
            <!-- Schedule Information -->
            <div class="col-lg-5">
                <div class="card p-2">
                    <h4 class="card-title d-flex border-bottom py-2">Schedule Information</h4>
                    <div class="card-body p-2">
                        <p class="d-flex gap-2">Data & Time: <strong> {{ $schedule->departure_time }}</strong></p>
                        <p class="">From {{ $schedule->route->originTerminal->name }} to
                            {{ $schedule->route->destinationTerminal->name }}</p>
                        <p class="d-flex">
                            <span class="text-gray">Available Capacity: </span> {{ $schedule->total_seats }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Seat Selection -->
            <div class="col-lg-7 h-100">
                <div class="card p-4 pl-2">
                    <h4 class="card-title d-flex border-bottom pb-2">Bus Seat Status</h4>
                    <form class="p-3" action="{{ route('schedules.store-seats') }}" method="POST">
                        @csrf
                        <input type="hidden" name="schedule_id" value={{ $schedule->id }} />

                        <!-- Determine seat layout logic -->
                        @php
                            $seatNumber = 1;
                            $totalSeats = $schedule->total_seats;
                        @endphp

                        <!-- Create rows for seats -->
                        <div class="container">
                            @while ($seatNumber <= $totalSeats)
                                <div class="row">
                                    <!-- Case 1: If total seats are less than 30 (show only in columns 1, 3, and 4) -->
                                    @if ($totalSeats <= 30)
                                        <div class="col-3">
                                            @if ($seatNumber <= $totalSeats)
                                                @php
                                                    $seat = $seats->get($seatNumber);
                                                    $isBooked = $seat && !is_null($seat->gender);
                                                    $seatGender = $seat ? ucfirst($seat->gender) : null;
                                                @endphp

                                                @if ($isBooked)
                                                    <!-- Booked seat -->
                                                    <button type="button" class="btn btn-danger w-100 mb-2" disabled>
                                                        {{ $seatNumber }} ({{ $seatGender }})
                                                    </button>
                                                @else
                                                    <!-- Available seat -->
                                                    <label class="btn btn-outline-success w-100 mb-2">
                                                        <input type="checkbox" name="selected_seats[]"
                                                            value="{{ $seatNumber }}">
                                                        {{ $seatNumber }}
                                                    </label>
                                                @endif
                                            @endif
                                            @php $seatNumber++; @endphp
                                        </div>

                                        <!-- Empty Column (col-2) -->
                                        <div class="col-3"></div>

                                        <!-- Column 3 -->
                                        <div class="col-3">
                                            @if ($seatNumber <= $totalSeats)
                                                @php
                                                    $seat = $seats->get($seatNumber);
                                                    $isBooked = $seat && !is_null($seat->gender);
                                                    $seatGender = $seat ? ucfirst($seat->gender) : null;
                                                @endphp

                                                @if ($isBooked)
                                                    <button type="button" class="btn btn-danger w-100 mb-2" disabled>
                                                        {{ $seatNumber }} ({{ $seatGender }})
                                                    </button>
                                                @else
                                                    <label class="btn btn-outline-success w-100 mb-2">
                                                        <input type="checkbox" name="selected_seats[]"
                                                            value="{{ $seatNumber }}">
                                                        {{ $seatNumber }}
                                                    </label>
                                                @endif
                                            @endif
                                            @php $seatNumber++; @endphp
                                        </div>

                                        <!-- Column 4 -->
                                        <div class="col-3">
                                            @if ($seatNumber <= $totalSeats)
                                                @php
                                                    $seat = $seats->get($seatNumber);
                                                    $isBooked = $seat && !is_null($seat->gender);
                                                    $seatGender = $seat ? ucfirst($seat->gender) : null;
                                                @endphp

                                                @if ($isBooked)
                                                    <button type="button" class="btn btn-danger w-100 mb-2" disabled>
                                                        {{ $seatNumber }} ({{ $seatGender }})
                                                    </button>
                                                @else
                                                    <label class="btn btn-outline-success w-100 mb-2">
                                                        <input type="checkbox" name="selected_seats[]"
                                                            value="{{ $seatNumber }}">
                                                        {{ $seatNumber }}
                                                    </label>
                                                @endif
                                            @endif
                                            @php $seatNumber++; @endphp
                                        </div>
                                    @else
                                        <!-- Case 2: If total seats are more than 30 (regular 4-column layout) -->
                                        @for ($i = 0; $i < 4 && $seatNumber <= $totalSeats; $i++, $seatNumber++)
                                            <div class="col-3">
                                                @php
                                                    $seat = $seats->get($seatNumber);
                                                    $isBooked = $seat && !is_null($seat->gender);
                                                    $seatGender = $seat ? ucfirst($seat->gender) : null;
                                                @endphp

                                                @if ($isBooked)
                                                    <button type="button" class="btn btn-danger w-100 mb-2" disabled>
                                                        {{ $seatNumber }} ({{ $seatGender }})
                                                    </button>
                                                @else
                                                    <label class="btn btn-outline-success w-100 mb-2">
                                                        <input type="checkbox" name="selected_seats[]"
                                                            value="{{ $seatNumber }}">
                                                        {{ $seatNumber }}
                                                    </label>
                                                @endif
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            @endwhile
                        </div>

                        <div class="mt-4 d-flex">
                            <button type="submit" class="btn btn-primary ">Reserve Selected Seats</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Any additional JS can go here
        });
    </script>
@endpush
