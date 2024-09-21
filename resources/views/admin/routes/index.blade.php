@extends('admin.layouts.master')

@section('content')
<div class="container mb-4">
    <h1 class="my-4">Route List</h1>
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
               {{ $message}}
            </div>
            @endforeach
        
        @endforeach  
    @endif

    <!-- Button to trigger the modal -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalRouteForm">
        Add New Route
    </button>
    @php
        use Carbon\Carbon;
    @endphp
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Origin Terminal</th>
                <th>Destination Terminal</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($routes as $route)
                @php
                // Convert the duration in minutes into a time format
                $durationInMinutes = $route->duration;

                // Create a Carbon instance at the zero time and add the duration in minutes
                $duration = Carbon::createFromTime(0, 0)->addMinutes($durationInMinutes);
                
                // Format the duration to show hours and minutes
                $formattedDuration = $duration->format('H') . 'h ' . $duration->format('i') . 'm';
            @endphp
            <tr>
                <td>{{ $route->id }}</td>
                <td>{{ $route->originTerminal->name }}</td>
                <td>{{ $route->destinationTerminal->name }}</td>
                <td>{{ $route->price }}</td>
                
                <td>{{ $formattedDuration }}</td>
                <td>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editRouteModal"
                            data-id="{{ $route->id }}"
                            data-origin_terminal_id="{{ $route->origin_terminal_id }}"
                            data-destination_terminal_id="{{ $route->destination_terminal_id }}"
                            data-price="{{ $route->price }}"
                            data-duration="{{ $route->duration }}">
                        Edit
                    </button>

                    <!-- Delete Form -->
                    <form action="{{ route('routes.destroy', $route->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Route Modal -->
    <div class="modal fade" id="modalRouteForm" tabindex="-1" aria-labelledby="modalRouteFormLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2" id="modalRouteFormLabel">Add a New Route</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-5 pt-0">
                    <form action="{{ route('routes.store') }}" method="POST">
                        @csrf
                        <!-- Origin Terminal Field -->
                        <div class="form-floating mb-3">
                            <select class="form-control rounded-3" id="origin_terminal_id" name="origin_terminal_id" >
                                <option value="" disabled selected>Select Origin Terminal</option>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                                @endforeach
                            </select>
                            <label for="origin_terminal_id">Origin Terminal</label>
                        </div>

                        <!-- Destination Terminal Field -->
                        <div class="form-floating mb-3">
                            <select class="form-control rounded-3" id="destination_terminal_id" name="destination_terminal_id">
                                <option value="" disabled selected>Select Destination Terminal</option>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                                @endforeach
                            </select>
                            <label for="destination_terminal_id">Destination Terminal</label>
                        </div>

                        <!-- Price Field -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control rounded-3" id="price" name="price" placeholder="Price" >
                            <label for="price">Price</label>
                        </div>

                        <!-- Duration Field -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="duration" name="duration" placeholder="Duration" >
                            <label for="duration">Duration</label>
                        </div>

                        <!-- Submit Button -->
                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Add Route</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Route Modal -->
    <div class="modal fade" id="editRouteModal" tabindex="-1" aria-labelledby="editRouteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2" id="editRouteModalLabel">Edit Route</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-5 pt-0">
                    <form id="editRouteForm" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Origin Terminal Field -->
                        <div class="form-floating mb-3">
                            <select class="form-control rounded-3" id="editOriginTerminalId" name="origin_terminal_id" required>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                                @endforeach
                            </select>
                            <label for="editOriginTerminalId">Origin Terminal</label>
                        </div>

                        <!-- Destination Terminal Field -->
                        <div class="form-floating mb-3">
                            <select class="form-control rounded-3" id="editDestinationTerminalId" name="destination_terminal_id" required>
                                @foreach($terminals as $terminal)
                                    <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                                @endforeach
                            </select>
                            <label for="editDestinationTerminalId">Destination Terminal</label>
                        </div>

                        <!-- Price Field -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control rounded-3" id="editPrice" name="price" placeholder="Price" required>
                            <label for="editPrice">Price</label>
                        </div>

                        <!-- Duration Field -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="editDuration" name="duration" placeholder="Duration" required>
                            <label for="editDuration">Duration</label>
                        </div>

                        <!-- Submit Button -->
                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Update Route</button>
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
    // Edit Route Modal
    $('#editRouteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);

        // Get data attributes from the clicked button
        var routeId = button.data('id');
        var originTerminalId = button.data('origin_terminal_id');
        var destinationTerminalId = button.data('destination_terminal_id');
        var price = button.data('price');
        var duration = button.data('duration');

        // Set values in the modal's input fields
        $('#editOriginTerminalId').val(originTerminalId);
        $('#editDestinationTerminalId').val(destinationTerminalId);
        $('#editPrice').val(price);
        $('#editDuration').val(duration);

        // Update the form action URL
        $('#editRouteForm').attr('action', '/admin/routes/' + routeId);
    });
});
</script>
@endpush
