@extends('admin.layouts.master')

@section('content')
<div class="container mb-4">
    <h1 class="my-4">Terminal List</h1>

    <!-- Button to trigger the modal for adding a new terminal -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalTerminalForm">
        Add New Terminal
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Terminal Name</th>
                <th>City</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($terminals as $terminal)
            <tr>
                <td>{{ $terminal->id }}</td>
                <td>{{ $terminal->name }}</td>
                <td>{{ $terminal->city->name }}, {{ $terminal->city->state }}</td> <!-- City name and state displayed -->
                <td>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editTerminalModal"
                            data-id="{{ $terminal->id }}"
                            data-terminal_name="{{ $terminal->name }}"
                            data-city_id="{{ $terminal->city_id }}">
                        Edit
                    </button>
                    <!-- Delete Form -->
                    <form action="{{ route('terminals.destroy', $terminal->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal for Adding a New Terminal -->
    <div class="modal fade" id="modalTerminalForm" tabindex="-1" aria-labelledby="modalTerminalFormLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
              <h1 class="fw-bold mb-0 fs-2" id="modalTerminalFormLabel">Add a New Terminal</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
      
            <div class="modal-body p-5 pt-0">
                <form action="{{ route('terminals.store') }}" method="POST">
                    @csrf
                    <!-- Terminal Name Field -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" id="terminalName" name="name" placeholder="Terminal Name" required>
                        <label for="terminalName">Terminal Name</label>
                    </div>
      
                    <!-- City Select Dropdown -->
                    <div class="form-floating mb-3">
                        <select class="form-select" id="cityId" name="city_id" required>
                            <option value="" disabled selected>Select City</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->state }}</option>
                            @endforeach
                        </select>
                        <label for="cityId">City</label>
                    </div>
      
                    <!-- Submit Button -->
                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Add Terminal</button>
                </form>
            </div>
          </div>
        </div>
      </div>

    <!-- Modal for Editing a Terminal -->
    <div class="modal fade" id="editTerminalModal" tabindex="-1" aria-labelledby="editTerminalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
              <h1 class="fw-bold mb-0 fs-2" id="editTerminalModalLabel">Edit Terminal</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
      
            <div class="modal-body p-5 pt-0">
              <form id="editTerminalForm" method="POST">
                @csrf
                @method('PUT')

                <!-- Terminal Name Field -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="editTerminalName" name="name" placeholder="Terminal Name" required>
                    <label for="editTerminalName">Terminal Name</label>
                </div>

                <!-- City Select Dropdown -->
                <div class="form-floating mb-3">
                    <select class="form-select" id="editCityId" name="city_id" required>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->state }}</option>
                        @endforeach
                    </select>
                    <label for="editCityId">City</label>
                </div>

                <!-- Submit Button -->
                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Update Terminal</button>
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
    // Trigger modal for editing a terminal
    $('#editTerminalModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        // Get the values from the button's data attributes
        var terminalId = button.data('id');
        var terminalName = button.data('terminal_name');
        var cityId = button.data('city_id');

        // Set the values in the modal's input fields
        $('#editTerminalName').val(terminalName);
        $('#editCityId').val(cityId);

        // Update the form action URL
        $('#editTerminalForm').attr('action', '/admin/terminals/' + terminalId);
    });
});
</script>
@endpush
