@extends('frontend.layouts.master')
@section('title')
    Search - Tickets
@endsection
@section('content')
    <div class="container">

        @if (session()->get('errors'))
            @foreach (session()->get('errors')->getMessages() as $key => $messages)
                @foreach ($messages as $message)
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @endforeach
            @endforeach
        @endif

        <div class="row g-3 ">
            <!-- Schedule Information Card -->
            <div class="col-lg-5">
                <div class="card p-3">
                    <h4>Schedule Information</h4>
                    <!-- Check if schedule_information exists in the session -->
                    @if (session('schedule_information'))
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Origin:
                                </strong>{{ session('schedule_information')['route']['origin_terminal']['name'] }}</li>
                            <li class="list-group-item"><strong>Destination:
                                </strong>{{ session('schedule_information')['route']['destination_terminal']['name'] }}</li>
                            <li class="list-group-item"><strong>Departure Time:
                                </strong>{{ session('schedule_information')['departure_time'] }}</li>
                            <li class="list-group-item"><strong>Total Seats:
                                </strong>{{ session('schedule_information')['total_seats'] }}</li>
                            <li class="list-group-item"><strong>Price:
                                </strong>${{ session('schedule_information')['route']['price'] }}</li>
                        </ul>
                    @else
                        <p>No schedule information available.</p>
                    @endif

                    <!-- Display Selected Seats -->
                    @if (session('selected_seats'))
                        <h5 class="mt-3">Selected Seats</h5>
                        <ul class="list-group">
                            @foreach (session('selected_seats') as $seat)
                                <li class="list-group-item">Seat Number: {{ $seat }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p>No seats selected.</p>
                    @endif
                </div>
            </div>

            <!-- User Contact Information Form for Each Seat -->
            <div class="col-lg-7  h-100">
                <div class="card p-3">
                    <form action="{{ route('booking.confirmation') }}" method="POST">
                        @csrf
                        <h4>Passenger Information for Selected Seats</h4>

                        <!-- First User Full Information -->
                        <h5 class="mt-4">Seat Number: {{ session('selected_seats')[0] }}</h5>

                        <div class="row mb-3">
                            <!-- Full Name -->
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label d-flex">Full Name</label>
                                <input type="text" class="form-control" id="name" name="passengers[0][name]"
                                    placeholder="John Doe" required
                                    value="{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}">
                            </div>

                            <!-- Phone Number -->
                            <div class="col-12 mb-2">
                                <label for="phone" class="form-label d-flex">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="passengers[0][phone]"
                                    placeholder="123-456-7890" value="{{ Auth::user()->phone ?? '' }}">
                            </div>

                            <!-- National Number -->
                            <div class="col-12 mb-2">
                                <label for="national_num" class="form-label d-flex">National
                                    Number</label>
                                <input type="text" class="form-control" id="national_num"
                                    name="passengers[0][national_num]" placeholder="National ID"
                                    value="{{ Auth::user()->national_num ?? '' }}" required>
                            </div>

                            <!-- Gender -->
                            <div class="col-12 mb-2">
                                <label for="gender" class="form-label d-flex">Gender</label>
                                <select name="passengers[0][gender]" id="gender" class="form-control" required>
                                    <option value="" disabled>Select Gender</option>
                                    <option value="male" {{ Auth::user()->gender === 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ Auth::user()->gender === 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>

                            <!-- Hidden Seat Number -->
                            <input type="hidden" name="passengers[0][seat_number]"
                                value="{{ session('selected_seats')[0] }}">
                        </div>

                        <!-- Loop through remaining passengers and get national_num, full_name, and gender -->
                        @foreach (session('selected_seats') as $index => $seat)
                            @if ($index > 0)
                                <!-- Skip the first passenger -->
                                <h5 class="mt-4">Seat Number: {{ $seat }}</h5>

                                <div class="row mb-3">
                                    <!-- Full Name -->
                                    <div class="col-12 mb-2">
                                        <label for="name_{{ $index }}" class="form-label d-flex">Full Name</label>
                                        <input type="text" class="form-control" id="name_{{ $index }}"
                                            name="passengers[{{ $index }}][name]" placeholder="John Doe" required>
                                    </div>

                                    <!-- National Number -->
                                    <div class="col-12 mb-2">
                                        <label for="national_num_{{ $index }}" class="form-label d-flex">National
                                            Number</label>
                                        <input type="text" class="form-control" id="national_num_{{ $index }}"
                                            name="passengers[{{ $index }}][national_num]" placeholder="National ID"
                                            required>
                                    </div>

                                    <!-- Gender -->
                                    <div class="col-12 mb-2">
                                        <label for="gender_{{ $index }}" class="form-label d-flex">Gender</label>
                                        <select name="passengers[{{ $index }}][gender]"
                                            id="gender_{{ $index }}" class="form-control" required>
                                            <option value="" disabled>Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>

                                    <!-- Hidden Seat Number -->
                                    <input type="hidden" name="passengers[{{ $index }}][seat_number]"
                                        value="{{ $seat }}">
                                </div>
                            @endif
                        @endforeach

                        <!-- Submit Button -->
                        <div class="mt-4 d-flex">
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
            // Any custom JavaScript can go here
        });
    </script>
@endpush
