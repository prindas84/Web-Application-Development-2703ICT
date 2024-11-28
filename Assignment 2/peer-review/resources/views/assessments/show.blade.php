@extends('layouts.master')

@section('title')
    Assessment: {{ $assessment->assessment_title }} - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div id="assessment-show-page" class="container mt-5 footer-space">
        <div class="py-4">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header">
                    <h5 class="mb-0">Assessment: {{ $assessment->assessment_title }}</h5>
                </div>
                <div class="card-body">
                    <!-- Success and Error messages -->
                    @if (session('success'))
                        <div class="alert alert-success" id="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger" id="error-message">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Display the assessment details -->
                    <p class="card-text"><strong>Instruction:</strong> {!! nl2br(e($assessment->assessment_instruction)) !!}</p>
                    <p class="card-text"><strong>Course:</strong> <a href="{{ route('course.show', $assessment->course->id) }}">{{ $assessment->course->course_name }}</a></p>
                    <p class="card-text"><strong>Assessment Due:</strong> {{ \Carbon\Carbon::parse($assessment->due_date)->format('d-m-Y') }} @ {{ \Carbon\Carbon::parse($assessment->due_time)->format('h:i A') }}</p>
                    @if($assessment->type == "teacher-select" && $assessment->group_size > 0)
                        <p class="card-text"><strong>Reviews Required:</strong> All Group Members</p>
                    @else
                        <p class="card-text"><strong>Reviews Required:</strong> {{ $reviewsRequired == -1 ? "Pending" : $reviewsRequired }}</p>
                    @endif
                    @if(auth()->user()->role === 'faculty')
                        <p class="card-text"><strong>Max Score:</strong> {{ $assessment->max_score }}</p>
                        <p class="card-text" style="text-transform: capitalize;"><strong>Type:</strong> {{ ucfirst($assessment->type) }}</p>
                    @else
                        <p class="card-text"><strong>Assessment Grade:</strong> {{ $userScore == -1 ? "Pending" : $userScore }}</p>
                    @endif
                    
                    <!-- If the user is a faculty member, display edit and delete options -->
                    @if(auth()->user()->role === 'faculty')
                        <div class="mt-4 d-flex gap-3">
                            @if($editable)
                                <!-- Edit Assessment Button -->
                                <form action="{{ route('assessment.edit', $assessment->id) }}" method="GET">
                                    <button type="submit" class="btn btn-warning">Edit Assessment</button>
                                </form>
                            @endif
                            <!-- Delete Assessment Button -->
                            <form action="{{ route('assessment.destroy', $assessment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Assessment</button>
                            </form>
                        </div>
                        @if(!$editable)
                            <div class="alert alert-danger" style="margin-top: 25px;">
                                <p style="margin-bottom: 0px;"><strong>PLEASE NOTE: </strong>This assessment cannot be edited at this time as it has completed reviews.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @if(auth()->user()->role === 'student')
            <div class="py-4">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-header">
                        <h5 class="mb-0">Peer Reviews</h5>
                    </div>
                    <div class="card-body">
                    @if($remainingReviews > 0)
                        <div class="alert alert-danger">
                            <p class="card-text"><strong>Reviews Remaining:</strong> {{ $remainingReviews }}</p>
                        </div>
                        @include('assessments.partials.add-student-review')
                    @elseif($reviewsRequired == -1)
                        <div class="alert alert-danger">
                            <p class="card-text">You have not been assigned a group. Please check back later.</p>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <p class="card-text">Assessment Complete!</p>
                        </div>
                    @endif
                    @include('assessments.partials.view-student-reviews')
                    </div>
                </div>
            </div>
        @endif
        @if(auth()->user()->role === 'faculty')
            @if($assessment->type === 'student-select')
                <div class="py-4">
                    <div class="card shadow-sm rounded-lg" id="student-submissions">
                        <div class="card-header">
                            <h5 class="mb-0">Student Submissions</h5>
                        </div>
                        <div class="card-body">
                            @include('assessments.partials.view-student-submissions')
                        </div>
                    </div>
                </div>
            @else
                @if($editable)
                    <div class="py-4">
                        <div class="card shadow-sm rounded-lg">
                            <div class="card-header">
                                <h5 class="mb-0">Create Groups</h5>
                            </div>
                            <div class="card-body">
                                @include('assessments.partials.create-groups')
                            </div>
                        </div>
                    </div>
                @endif
                <div class="py-4">
                    <div class="card shadow-sm rounded-lg" id="student-submissions">
                        <div class="card-header">
                            <h5 class="mb-0">Student Groups</h5>
                        </div>
                        <div class="card-body">
                            @include('assessments.partials.view-student-groups')
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
