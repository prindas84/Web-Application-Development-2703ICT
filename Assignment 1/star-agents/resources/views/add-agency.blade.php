@extends('layouts.master')

@section('title')
    Add Agency - WAD Assignment 1 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="container page-content">
        <div class="page-header montserrat-heading">
            <h2>Add Agency</h2>
        </div>

        <!-- Display success message if present -->
        @if(session('success'))
            <div class="alert alert-success">
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
            <div class="alert alert-danger" id="error-alert">
                {!! session('error') !!}
            </div>
            <script>
                // Hide error message after 3 seconds
                setTimeout(function() {
                    document.getElementById('error-alert').style.display = 'none';
                }, 3000);
            </script>
        @endif

        <!-- Form to add a new agency -->
        <form id="add-agency-form" action="{{ route('add-agency') }}" method="POST">
            @csrf <!-- CSRF token for security -->

            <div class="form-group">
                <label for="agency_name">Agency Name*</label>
                <input type="text" class="form-control" id="agency_name" name="agency_name" value="{{ old('agency_name') }}">
            </div>

            <div class="form-group">
                <label for="agency_address">Agency Address*</label>
                <input type="text" class="form-control" id="agency_address" name="agency_address" value="{{ old('agency_address') }}">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Add Agency</button>
        </form>

        <!-- List of all Agencies -->
        <div class="mt-5">
            <h3>All Agencies</h3>
            @if(!empty($data))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Agency Name</th>
                            <th>Agency Address</th>
                            <th>Average Rating</th>
                            <th>Delete Record</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Display each agency's details -->
                        @foreach($data as $agency)
                            <tr onclick="window.location='{{ route('view-agency', $agency->agency_id) }}'" style="cursor:pointer;">
                                <td>{{ $agency->agency_name }}</td>
                                <td>{{ $agency->agency_address }}</td>
                                <!-- Display message if no reviews are recorded -->
                                @if(is_null($agency->average_rating))
                                    <td>NO REVIEWS RECORDED</td>
                                @else
                                    <td>{{ number_format($agency->average_rating, 1) }}</td>
                                @endif
                                <td>
                                    <form action="{{ route('delete-agency', $agency->agency_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link p-0 delete-agency" onclick="return confirm('Are you sure you want to delete this agency record?')">
                                            <img src="{{ asset('images/delete.png') }}" alt="Delete">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No agencies found.</p> <!-- Message if no agencies are in the database -->
            @endif
        </div>
    </div>
@endsection
