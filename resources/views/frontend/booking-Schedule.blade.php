@extends('frontend.layouts.master')
@section('title')
    Search - Tickets
@endsection
@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

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
                                </strong>{{ session('schedule_information')['route']['origin_terminal']['name'] }}</li>
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

            <!-- User Contact Information Form -->
            <div class="col-lg-7  h-100">
                <div class="card p-3">
                    <form action="{{ route('booking.confirmation') }}" method="POST">
                        @csrf
                        <h4>User Contact Information</h4>

                        <div class="row mb-3">
                            <!-- Name -->
                            <div class="col-12 mb-2">
                                <label for="name" class="form-label d-flex">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="John Doe" required value="{{ Auth::user()->first_name ?? '' }}">
                            </div>

                            <div class="col-12">
                                <label for="phone" class="form-label d-flex">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="123-456-7890" required value="{{ Auth::user()->phone ?? '' }}">
                            </div>
                            <!-- Gender -->
                            <div class="col-12">
                                <label for="gender" class="form-label d-felx">Gender</label>
                                <select name="gender" id="gender" class="form-control" required>
                                    <option value="" disabled>Select Gender</option>
                                    <option {{ Auth::user()->gender === 'male' && 'selected' }} value="male">Male
                                    </option>
                                    <option {{ Auth::user()->gender === 'female' && 'selected' }} value="female">Female
                                    </option>
                                </select>
                            </div>
                        </div>

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
