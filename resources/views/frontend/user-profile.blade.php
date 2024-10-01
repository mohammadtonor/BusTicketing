@extends('frontend.layouts.master')

@section('title')
    Update User Information
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

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-3">
            <!-- User Information Form -->
            <div class="col-lg-7">
                <div class="card p-3">
                    <form action="{{ route('user.profile.update', Auth::id()) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <h4>Update User Information</h4>

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label d-flex d-flex ">Full Name</label>
                            <input type="text" class="form-control " id="name" name="name" placeholder="John Doe"
                                required
                                value="{{ old('name', Auth::user()->first_name . ' ' . Auth::user()->last_name) }}">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label d-flex">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="email@example.com" required value="{{ old('email', Auth::user()->email) }}">
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="phone" class="form-label d-flex">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="123-456-7890" value="{{ old('phone', Auth::user()->phone) }}">
                        </div>

                        <!-- National Number -->
                        <div class="mb-3">
                            <label for="national_num" class="form-label d-flex">National Number</label>
                            <input type="text" class="form-control" id="national_num" name="national_num"
                                placeholder="National ID" value="{{ old('national_num', Auth::user()->national_num) }}"
                                required>
                        </div>

                        <!-- Gender -->
                        <div class="mb-3">
                            <label for="gender" class="form-label d-flex">Gender</label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="" disabled>Select Gender</option>
                                <option value="male"
                                    {{ old('gender', Auth::user()->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female"
                                    {{ old('gender', Auth::user()->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-4 d-flex">
                            <button type="submit" class="btn btn-primary">Update Information</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
