@extends('layouts.master')

@section('title')
    Add Agent - WAD Assignment 1 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="container page-content">
        <div class="page-header montserrat-heading">
            <h2>Add Agent</h2>
        </div>

        <!-- Display success message if present -->
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {!! session('success') !!}
            </div>
            <script>
                // Reload the page after 2 seconds
                setTimeout(function() {
                    location.reload();
                }, 2000);
            </script>
        @endif

        <!-- Display error message if present -->
        @if(session('error'))
            <div class="alert alert-danger mt-3" id="error-alert">
                {!! session('error') !!}
            </div>
            <script>
                // Hide error message after 3 seconds
                setTimeout(function() {
                  document.getElementById('error-alert').style.display = 'none';
                }, 3000);
            </script>
        @endif

        <!-- Form to add a new agent -->
        <form id="add-agent-form" action="{{ route('add-agent') }}" method="POST">
            @csrf <!-- CSRF token for security -->

            <div class="form-group">
                <label for="first_name">First Name*</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}">
            </div>

            <div class="form-group">
                <label for="surname">Surname*</label>
                <input type="text" class="form-control" id="surname" name="surname" value="{{ old('surname') }}">
            </div>

            <div class="form-group">
                <label for="position">Position*</label>
                <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}">
            </div>

            <div class="form-group">
                <label for="biography">Biography*</label>
                <textarea class="form-control" id="biography" name="biography">{{ old('biography') }}</textarea>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number*</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
            </div>

            <div class="form-group">
                <label for="email">Email Address*</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}">
            </div>

            <!-- Dropdown to select an agency for the agent -->
            <div class="form-group">
                <label for="agency_id">Select Agency*</label>
                <select class="form-control" id="agency_id" name="agency_id">
                    <option value="">[ Select Agency ]</option>
                    @foreach($agencyData as $agency)
                      <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                          {{ $agency->agency_name }}
                      </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Add Agent</button>
        </form>
    </div>
@endsection
