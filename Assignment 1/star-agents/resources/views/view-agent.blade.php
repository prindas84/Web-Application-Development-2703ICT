@extends('layouts.master')

@section('title')
    Agent Details - WAD Assignment 1 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="container">
        <div class="page-header montserrat-heading">
            <h2>Agent Details</h2>
        </div>

        <!-- Display error message for delete operation, if any -->
        @if(session('error-delete'))
            <div class="alert alert-danger mt-3" id="error-alert">
                {!! session('error-delete') !!}
            </div>
            <script>
                // Hide error message after 3 seconds
                setTimeout(function() {
                  document.getElementById('error-alert').style.display = 'none';
                }, 3000);
            </script>
        @endif
        
        <!-- Display agent's details with profile image -->
        <div class="clearfix profile-content">
            <img src="{{ asset('images/profile.jpg') }}" alt="Agent Image" class="ml-3 mb-3 profile-avatar">

            <div>
                <div class="mb-3">
                    <h4>First Name:</h4>
                    <p>{{ $agent->first_name }}</p>
                </div>

                <div class="mb-3">
                    <h4>Surname:</h4>
                    <p>{{ $agent->surname }}</p>
                </div>

                <div class="mb-3">
                    <h4>Email:</h4>
                    <p>{{ $agent->email }}</p>
                </div>

                <div class="mb-3">
                    <h4>Phone:</h4>
                    <p>{{ $agent->phone }}</p>
                </div>

                <div class="mb-3">
                    <h4>Position:</h4>
                    <p>{{ $agent->position }}</p>
                </div>

                <div class="mb-3">
                    <h4>Biography:</h4>
                    <p>{{ $agent->biography }}</p>
                </div>

                <!-- Display agency details if available -->
                @if($agency)
                    <div class="mb-3">
                        <h4>Agency Name:</h4>
                        <p>{{ $agency->agency_name }}</p>
                    </div>

                    <div class="mb-3">
                        <h4>Agency Address:</h4>
                        <p>{{ $agency->agency_address }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Form to delete the agent listing -->
            <form action="{{ route('delete-agent', ['agentNumber' => $agent->id]) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm delete-agent" 
                    onclick="return confirm('Are you sure you want to delete this agent listing?')">Delete Agent</button>
            </form>
        </div>
    </div>

    <!-- Section to display agent reviews -->
    <div class="container review-listings">
        <div class="review-header">
            <h2>Reviews for {{ $agent->first_name }} {{ $agent->surname }}</h2>
        </div>

        <!-- Display agent reviews if available -->
        @if($reviews)
            @foreach ($reviews as $review)
                <div class="review mb-3 p-3 border rounded">
                    <h5>{{ $review->review_heading }} ({{ $review->rating }} {{ $review->rating == 1 ? 'Star' : 'Stars' }})</h5>
                    <p>{{ $review->review }}</p>
                    <!-- Display a warning if the review is flagged as fake -->
                    @if ($review->flagged_review === 1)
                        <p class="alert alert-danger mt-3"><strong>WARNING:</strong> This review has been flagged as a possible fake review.</p>
                    @endif
                    <small><strong>Reviewer:</strong> {{ $review->reviewer_name }}<br><strong>Submitted on:</strong> {{ $review->date }}</small>
                </div>
            @endforeach
        @else
            <!-- Message if no reviews are found -->
            <p>No reviews yet. Be the first to review this agent!</p>
        @endif
    </div>

    <!-- Section for submitting a new review -->
    <div class="container review-section" id="review-form">
        <div class="review-header">
            <h2>Submit a Review</h2>
        </div>

        <!-- Display success message for review submission -->
        @if(session('success-review'))
            <div class="alert alert-success mt-3">
                {!! session('success-review') !!}
            </div>
            <script>
                // Reload the page after 2 seconds
                setTimeout(function() {
                    location.reload();
                }, 2000);
            </script>
        @endif

        <!-- Display error message for review submission -->
        @if(session('error-review'))
            <div class="alert alert-danger mt-3" id="error-alert">
                {!! session('error-review') !!}
            </div>
            <script>
                // Hide error message after 3 seconds
                setTimeout(function() {
                  document.getElementById('error-alert').style.display = 'none';
                }, 3000);
            </script>
        @endif

        <!-- Form to submit a new review -->
        <form action="{{ route('view-agent', ['agentNumber' => $agent->id]) }}" method="POST">
            @csrf  <!-- Token for security -->

            <div class="form-group">
                <label for="reviewer_name">Reviewer's Name</label>
                <input type="text" class="form-control" id="reviewer_name" name="reviewer_name" 
                    value="{{ session('userName') ?? old('reviewer_name') }}">
            </div>

            <div class="form-group">
                <label for="review_heading">Review Heading</label>
                <input type="text" class="form-control" id="review_heading" name="review_heading" value="{{ old('review_heading') }}">
            </div>

            <div class="form-group">
                <label for="review_content">Review Content</label>
                <textarea class="form-control" id="review_content" name="review_content" rows="5">{{ old('review_content') }}</textarea>
            </div>

            <div class="form-group">
                <label for="star_rating">Rating</label>
                <select class="form-control" id="star_rating" name="star_rating">
                    <option value="1" {{ old('star_rating') == 1 ? 'selected' : '' }}>1 Star</option>
                    <option value="2" {{ old('star_rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                    <option value="3" {{ old('star_rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                    <option value="4" {{ old('star_rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                    <option value="5" {{ old('star_rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Submit Review</button>
        </form>
    </div>

@endsection
