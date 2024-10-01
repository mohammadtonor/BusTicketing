@extends('admin.layouts.master')
@section('title')
    Schedule
@endsection
@section('content')
    <div class="container mb-4">
        <h1 class="my-4">Schedule List</h1>

        <!-- Session Flash Message -->
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

        <!-- Button to trigger the modal -->
        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalScheduleForm">
            Add New Schedule
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bus</th>
                    <th>Route</th>
                    <th>Departure Time</th>
                    <th>Total Seats</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->id }}</td>
                        <td>{{ $schedule->bus->bus_name }}</td>
                        <td>{{ $schedule->route->originTerminal->name }} - {{ $schedule->route->destinationTerminal->name }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($schedule->departure_time)->format('Y-m-d H:i') }}</td>
                        <td>{{ $schedule->total_seats }}</td>
                        <td>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editScheduleModal" data-id="{{ $schedule->id }}"
                                data-bus_id="{{ $schedule->bus_id }}" data-route_id="{{ $schedule->route_id }}"
                                data-departure_time="{{ $schedule->departure_time }}"
                                data-total_seats="{{ $schedule->total_seats }}">
                                Edit
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>

                            <a href="{{ route('admin.bookings.reserved-passengers', $schedule->id) }}"
                                class="btn btn-primary ml-2">Show</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Add Schedule Modal -->
        <div class="modal fade" id="modalScheduleForm" tabindex="-1" aria-labelledby="modalScheduleFormLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header p-5 pb-4 border-bottom-0">
                        <h1 class="fw-bold mb-0 fs-2" id="modalScheduleFormLabel">Add New Schedule</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-5 pt-0">
                        <form action="{{ route('schedules.store') }}" method="POST">
                            @csrf
                            <!-- Bus Field -->
                            <div class="form-floating mb-3">
                                <select class="form-control rounded-3" id="bus_id" name="bus_id" required>
                                    <option value="" disabled selected>Select Bus</option>
                                    @foreach ($buses as $bus)
                                        <option value="{{ $bus->id }}">{{ $bus->bus_name }}</option>
                                    @endforeach
                                </select>
                                <label for="bus_id">Bus</label>
                            </div>

                            <!-- Route Field -->
                            <div class="form-floating mb-3">
                                <select class="form-control rounded-3" id="route_id" name="route_id" required>
                                    <option value="" disabled selected>Select Route</option>
                                    @foreach ($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->originTerminal->name }} -
                                            {{ $route->destinationTerminal->name }}</option>
                                    @endforeach
                                </select>
                                <label for="route_id">Route</label>
                            </div>

                            <!-- Start Date Field -->
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control rounded-3" id="start_date" name="start_date"
                                    required>
                                <label for="start_date">Start Date</label>
                            </div>

                            <!-- End Date Field -->
                            <div class="form-floating mb-3">
                                <input type="date" class="form-control rounded-3" id="end_date" name="end_date"
                                    required>
                                <label for="end_date">End Date</label>
                            </div>

                            <!-- Departure Time Field -->
                            <div class="form-floating mb-3">
                                <input type="time" class="form-control rounded-3" id="departure_time"
                                    name="departure_time" required>
                                <label for="departure_time">Departure Time</label>
                            </div>

                            <!-- Total Seats Field -->
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control rounded-3" id="total_seats" name="total_seats"
                                    required min="1">
                                <label for="total_seats">Total Seats</label>
                            </div>

                            <!-- Submit Button -->
                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Add Schedule</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Schedule Modal -->
        <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content rounded-4 shadow">
                    <div class="modal-header p-5 pb-4 border-bottom-0">
                        <h1 class="fw-bold mb-0 fs-2" id="editScheduleModalLabel">Edit Schedule</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-5 pt-0">
                        <form id="editScheduleForm" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Bus Field -->
                            <div class="form-floating mb-3">
                                <select class="form-control rounded-3" id="editBusId" name="bus_id">
                                    @foreach ($buses as $bus)
                                        <option value="{{ $bus->id }}">{{ $bus->bus_name }}</option>
                                    @endforeach
                                </select>
                                <label for="editBusId">Bus</label>
                            </div>

                            <!-- Route Field -->
                            <div class="form-floating mb-3">
                                <select class="form-control rounded-3" id="editRouteId" name="route_id">
                                    @foreach ($routes as $route)
                                        <option value="{{ $route->id }}">{{ $route->originTerminal->name }} -
                                            {{ $route->destinationTerminal->name }}</option>
                                    @endforeach
                                </select>
                                <label for="editRouteId">Route</label>
                            </div>

                            <!-- Departure Time Field -->
                            <div class="form-floating mb-3">
                                <input type="datetime-local" class="form-control rounded-3" id="editDepartureTime"
                                    name="departure_time">
                                <label for="editDepartureTime">Departure Time</label>
                            </div>

                            <!-- Total Seats Field -->
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control rounded-3" id="editTotalSeats"
                                    name="total_seats">
                                <label for="editTotalSeats">Total Seats</label>
                            </div>

                            <!-- Submit Button -->
                            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Update
                                Schedule</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Edit Schedule Modal
            $('#editScheduleModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);

                // Get data attributes from the clicked button
                var scheduleId = button.data('id');
                var busId = button.data('bus_id');
                var routeId = button.data('route_id');
                var departureTime = button.data('departure_time');
                var totalSeats = button.data('total_seats');

                // Set values in the modal's input fields
                $('#editBusId').val(busId);
                $('#editRouteId').val(routeId);
                $('#editDepartureTime').val(departureTime);
                $('#editTotalSeats').val(totalSeats);

                // Update the form action URL
                $('#editScheduleForm').attr('action', '/admin/schedules/' + scheduleId);
            });
        });
    </script>
@endpush
