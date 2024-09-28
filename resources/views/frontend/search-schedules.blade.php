@extends('frontend.layouts.master')
@section('title')
    Search - Tickets
@endsection
@section('content')
    <div class="container">
        <div class="row g-3 ">
            <div class="col-lg-4">
                <form id="search-form" action="{{ route('schedules.search') }}" method="POST" class="card p-3">
                    @csrf
                    <!-- Origin Terminal Field -->
                    <div class="form-floating mb-3">
                        <select class="form-control rounded-3" id="origin_terminal_id" name="origin_terminal_id" required>
                            <option value="" disabled selected>Select Origin Terminal</option>
                            @foreach ($terminals as $terminal)
                                <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                            @endforeach
                        </select>
                        <label for="origin_terminal_id">Origin Terminal</label>
                    </div>

                    <!-- Destination Terminal Field -->
                    <div class="form-floating mb-3">
                        <select class="form-control rounded-3" id="destination_terminal_id" name="destination_terminal_id"
                            required>
                            <option value="" disabled selected>Select Destination Terminal</option>
                            @foreach ($terminals as $terminal)
                                <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                            @endforeach
                        </select>
                        <label for="destination_terminal_id">Destination Terminal</label>
                    </div>

                    <!-- Departure Date Field -->
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control rounded-3" id="departure_date" name="departure_date"
                            required>
                        <label for="departure_date">Departure Date</label>
                    </div>

                    <!-- Submit Button -->
                    <div>

                        <button class=" mb-2 mt-2 btn btn-lg rounded-3 btn-primary" type="submit">Search Schedules</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-8  h-100">
                <div id="schedules-results" class="card p-3">
                    <p>No schedules found for the selected date.</p>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#search-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                console.log("ready to run");
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(), // Serialize the form data
                    success: function(response) {
                        // Clear the previous results
                        $('#schedules-results').empty();

                        // Check if schedules were found
                        if (response?.schedules?.length > 0) {
                            // Loop through each schedule and append it to the results div
                            $.each(response.schedules, function(index, schedule) {
                                const html = `
                                <div class="schedule-item my-2 p-3 border rounded">
                                    <div class="left-info ">
                                        <!-- Bus and Departure Time -->
                                        <div class="bus-info">
                                            <h5>Bus:</h5>
                                            <p class="mb-0">${schedule.bus.bus_name}</p>
                                        </div>
                                        <div class="departure-time">
                                            <h5>Departure Time:</h5>
                                            <p class="mb-0">${schedule.departure_time}</p>
                                        </div>
                                    </div>

                                    <div class="middle-info">
                                        <!-- Route -->
                                        <div class="route-info">
                                            <h5>Route:</h5>
                                            <p class="mb-0">
                                                ${schedule.route.origin_terminal.name} -> ${schedule.route.destination_terminal.name}
                                            </p>
                                        </div>

                                        <!-- Total Seats -->
                                        <div class="total-seats">
                                            <h5>Total Seats:</h5>
                                            <p class="mb-0">${schedule.total_seats}</p>
                                        </div>
                                    </div>

                                    <!-- Button -->
                                    <div class="action-button">
                                        <a href="/schedule/${schedule.id}" class="btn btn-lg rounded-3 btn-primary">Book Now</a>
                                    </div>
                                </div>`;

                                $('#schedules-results').append(html);
                            });

                        } else {
                            $('#schedules-results').append(
                                '<p>No schedules found for the selected date.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle any errors here
                        $('#schedules-results').empty();
                        $('#schedules-results').append(
                            '<p>An error occurred while searching for schedules.</p>');
                    }
                });
            });
        });
    </script>
@endpush
