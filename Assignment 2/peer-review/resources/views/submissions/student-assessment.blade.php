@extends('layouts.master')

@section('title', 'Student Assessment')

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
                
                <p><strong>Instruction:</strong> {!! nl2br(e($assessment->assessment_instruction)) !!}</p>
                <p><strong>Assessment Due:</strong> {{ \Carbon\Carbon::parse($assessment->due_date)->format('d-m-Y') }} @ {{ \Carbon\Carbon::parse($assessment->due_time)->format('h:i A') }}</p>
                <p><strong>Student:</strong> {{ $student->first_name }} {{ $student->surname }}</p>
                <p><strong>Status:</strong> {{ $studentAssessment->pivot->complete ? 'Complete' : 'Incomplete' }}</p>
                <a href="{{ route('assessment.show', $assessment->id) }}#student-submissions" class="btn btn-warning mt-3">Back to Assessment</a>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="card shadow-sm rounded-lg" id="student-submissions">
            <div class="card-header">
                <h5 class="mb-0">Assessment Score</h5>
            </div>
            <div class="card-body">
                @include('submissions.partials.update-score')
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="card shadow-sm rounded-lg" id="student-submissions">
            <div class="card-header">
                <h5 class="mb-0">Review Submissions</h5>
            </div>
            <div class="card-body">
                @include('submissions.partials.view-reviews-submissions')
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="card shadow-sm rounded-lg" id="student-submissions">
            <div class="card-header">
                <h5 class="mb-0">Review Submissions</h5>
            </div>
            <div class="card-body">
                @include('submissions.partials.view-review-received')
            </div>
        </div>
    </div>

</div>

        
@endsection
