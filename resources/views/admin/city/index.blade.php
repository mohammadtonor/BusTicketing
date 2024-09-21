@extends('admin.layouts.master')

@section('content')
<div class="container mb-4 p-4 h-50 w-100">
  <div>
    
    <h1 class="my-4">City List</h1>
    
    <!-- Button to trigger the modal for adding a new city -->
    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalCityForm">
      Add New City
    </button>
  </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>City Name</th>
                <th>State</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cities as $city)
            <tr>
                <td>{{ $city->id }}</td>
                <td>{{ $city->name }}</td>
                <td>{{ $city->state }}</td>
                <td>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editCityModal"
                            data-id="{{ $city->id }}"
                            data-city_name="{{ $city->name }}"
                            data-city_state="{{ $city->state }}">
                        Edit
                    </button>
                    <!-- Delete Form -->
                    <form action="{{ route('cities.destroy', $city->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal for Adding a New City -->
    <div class="modal fade" id="modalCityForm" tabindex="-1" aria-labelledby="modalCityFormLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
              <h1 class="fw-bold mb-0 fs-2" id="modalCityFormLabel">Add a New City</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
      
            <div class="modal-body p-5 pt-0">
                <form action="{{ route('cities.store') }}" method="POST">
                    @csrf
                    <!-- City Name Field -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" id="cityName" name="name" placeholder="City Name" required>
                        <label for="cityName">City Name</label>
                    </div>
      
                    <!-- State Field -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" id="cityState" name="state" placeholder="State" required>
                        <label for="cityState">State</label>
                    </div>
      
                    <!-- Submit Button -->
                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Add City</button>
                </form>
            </div>
          </div>
        </div>
      </div>

    <!-- Modal for Editing a City -->
    <div class="modal fade" id="editCityModal" tabindex="-1" aria-labelledby="editCityModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
              <h1 class="fw-bold mb-0 fs-2" id="editCityModalLabel">Edit City</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
      
            <div class="modal-body p-5 pt-0">
              <form id="editCityForm" method="POST">
                @csrf
                @method('PUT')

                <!-- City Name Field -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="editCityName" name="name" placeholder="City Name" required>
                    <label for="editCityName">City Name</label>
                </div>

                <!-- State Field -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="editCityState" name="state" placeholder="State" required>
                    <label for="editCityState">State</label>
                </div>

                <!-- Submit Button -->
                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Update City</button>
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
    // Ensure the modal is triggered for editing a city
    $('#editCityModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        // Get the values from the button's data attributes
        var cityId = button.data('id');
        var cityName = button.data('city_name');
        var cityState = button.data('city_state');

        // Set the values in the modal's input fields
        $('#editCityName').val(cityName);
        $('#editCityState').val(cityState);

        // Update the form action URL
        $('#editCityForm').attr('action', '/admin/cities/' + cityId);
    });
});
</script>
@endpush
