@extends('admin.layouts.master')

@section('content')
<div class="container mb-4">
    <h1 class="my-4">Bus List</h1>

    <!-- Button to trigger the modal -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalBusForm">
        Add New Bus
      </button>

    <table class="table table-bordered ">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bus Number</th>
                <th>Bus Name</th>
                <th>Total Seats</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buses as $bus)
            <tr>
                <td>{{ $bus->id }}</td>
                <td>{{ $bus->bus_number }}</td>
                <td>{{ $bus->bus_name }}</td>
                <td>{{ $bus->total_seats }}</td>
                <td>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editBusModal" 
                            data-id="{{ $bus->id }}" 
                            data-bus_number="{{ $bus->bus_number }}" 
                            data-bus_name="{{ $bus->bus_name }}" 
                            data-total_seats="{{ $bus->total_seats }}">
                        Edit
                    </button>
                    <form action="{{ route('buses.destroy', $bus->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Button to open modal -->
    <div class="modal fade" id="modalBusForm" tabindex="-1" aria-labelledby="modalBusFormLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
              <h1 class="fw-bold mb-0 fs-2" id="modalBusFormLabel">Add a New Bus</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
      
            <div class="modal-body p-5 pt-0">
                <form action="{{ route('buses.store') }}" method="POST">
                    @csrf
                <!-- Bus Number Field -->
                <div class="form-floating mb-3">
                  <input type="text" class="form-control rounded-3" id="busNumber" name="bus_number" placeholder="Bus Number" required>
                  <label for="busNumber">Bus Number</label>
                </div>
      
                <!-- Bus Name Field -->
                <div class="form-floating mb-3">
                  <input type="text" class="form-control rounded-3" id="busName" name="bus_name" placeholder="Bus Name" required>
                  <label for="busName">Bus Name</label>
                </div>
      
                <!-- Total Seats Field -->
                <div class="form-floating mb-3">
                  <input type="number" class="form-control rounded-3" id="totalSeats" name="total_seats" placeholder="Total Seats" required>
                  <label for="totalSeats">Total Seats</label>
                </div>
      
                <!-- Submit Button -->
                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Add Bus</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal fade" id="editBusModal" tabindex="-1" aria-labelledby="editBusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
              <h1 class="fw-bold mb-0 fs-2" id="editBusModalLabel">Edit Bus</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
      
            <div class="modal-body p-5 pt-0">
              <form id="editBusForm" method="POST">
                @csrf
                @method('PUT')
      
                <!-- Bus Number Field -->
                <div class="form-floating mb-3">
                  <input type="text" class="form-control rounded-3" id="editBusNumber" name="bus_number" placeholder="Bus Number" required>
                  <label for="editBusNumber">Bus Number</label>
                </div>
      
                <!-- Bus Name Field -->
                <div class="form-floating mb-3">
                  <input type="text" class="form-control rounded-3" id="editBusName" name="bus_name" placeholder="Bus Name" required>
                  <label for="editBusName">Bus Name</label>
                </div>
      
                <!-- Total Seats Field -->
                <div class="form-floating mb-3">
                  <input type="number" class="form-control rounded-3" id="editTotalSeats" name="total_seats" placeholder="Total Seats" required>
                  <label for="editTotalSeats">Total Seats</label>
                </div>
      
                <!-- Submit Button -->
                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Update Bus</button>
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
    // Ensure the modal is triggered
    $('#editBusModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);

        // Get the values from the button's data attributes
        var busId = button.data('id');
        var busNumber = button.data('bus_number');
        var busName = button.data('bus_name');
        var totalSeats = button.data('total_seats');

        // Set the values in the modal's input fields
        $('#editBusNumber').val(busNumber);
        $('#editBusName').val(busName);
        $('#editTotalSeats').val(totalSeats);

        // Update the form action URL
        $('#editBusForm').attr('action', '/admin/buses/' + busId);
    });
});

  </script>
  
@endpush
