@extends('frontend.layouts.master')
@section('title')
    Search - Tickets
@endsection
@section('content')
    <div class="container">
        <div class="row g-3 ">
            <div class="col-lg-5">
                <div class="card ">
                    <div class="card-body">
                        <h4 class="card-title">schedule Information</h4>
                        <p class="">{{ $schedule->departure_time }}</p>
                        <p class="">from {{ $schedule->route->originTerminal->name }} to
                            {{ $schedule->route->destinationTerminal->name }}</p>
                        <p class="d-flex ">
                            <span class="text-gray">Available Capacity: </span> {{ $schedule->total_seats }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7  h-100">
                <div class="card">
                    <form class="p-3" action="{{ route('schedules.store-seats') }}" method="POST">
                        @csrf
                        <input type="hidden" name="schedule_id" value={{ $schedule->id }} />
                        <div class="row">
                            @php $seatNumber = 1; @endphp
                            @while ($seatNumber <= $schedule->total_seats)
                                <div class="col-4 mb-3">
                                    <!-- Column for 3 seats -->
                                    @for ($i = 0; $i < 3 && $seatNumber <= $schedule->total_seats; $i++, $seatNumber++)
                                        @php
                                            $seat = $seats->get($seatNumber); // Use data passed from controller
                                            $isBooked = $seat && !is_null($seat->gender); // If seat has a gender, it is booked
                                            $seatGender = $seat ? ucfirst($seat->gender) : null;
                                        @endphp

                                        @if ($isBooked)
                                            <!-- Display booked seat with gender -->
                                            <button type="button" class="btn btn-danger w-100 mb-2" disabled>
                                                {{ $seatNumber }} (Booked by {{ $seatGender }})
                                            </button>
                                        @else
                                            <!-- Display available seat -->
                                            <label class="btn btn-outline-success w-100 mb-2">
                                                <input type="checkbox" name="selected_seats[]" value="{{ $seatNumber }}">
                                                {{ $seatNumber }}
                                            </label>
                                        @endif
                                    @endfor
                                </div>
                            @endwhile
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Reserve Selected Seats</button>
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

        });
    </script>
@endpush
