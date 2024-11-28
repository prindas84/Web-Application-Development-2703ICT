@extends('layouts.master')

@section('title')
    Edit Assessment - {{ $assessment->assessment_title }} - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="container mt-5 footer-space">
        <div class="py-4">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header">
                    <h5 class="mb-0">Edit Assessment: {{ $assessment->assessment_title }}</h5>
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
                    
                    @if($editable)  
                        <!-- Form to edit the assessment details -->
                        <form action="{{ route('assessment.update', $assessment->id) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- Specify that this form will use the PUT method for updating the assessment -->

                            <!-- Field for editing the assessment title -->
                            <div class="mb-3">
                                <label for="assessment_title" class="form-label">Assessment Title</label>
                                <input type="text" class="form-control" id="assessment_title" name="assessment_title" value="{{ $assessment->assessment_title }}" required>
                            </div>

                            <!-- Field for editing the assessment instruction -->
                            <div class="mb-3">
                                <label for="assessment_instruction" class="form-label">Assessment Instruction</label>
                                <textarea class="form-control" id="assessment_instruction" name="assessment_instruction" rows="4" required>{{ $assessment->assessment_instruction }}</textarea>
                            </div>

                            <!-- Field for editing the due date -->
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" value="{{ \Carbon\Carbon::parse($assessment->due_date)->format('Y-m-d') }}" required>
                            </div>

                            <!-- Field for editing the due time -->
                            <div class="mb-3">
                                <label for="due_time" class="form-label">Due Time</label>
                                <input type="time" class="form-control" id="due_time" name="due_time" value="{{ $assessment->due_time }}" required>
                            </div>

                            <!-- Field for editing the number of reviews required -->
                            <div class="mb-3">
                                <label for="reviews_required" class="form-label">Reviews Required</label>
                                <input type="number" class="form-control" id="reviews_required" name="reviews_required" value="{{ $assessment->reviews_required }}" required>
                            </div>

                            <!-- Field for editing the max score -->
                            <div class="mb-3">
                                <label for="max_score" class="form-label">Max Score</label>
                                <input type="number" class="form-control" id="max_score" name="max_score" value="{{ $assessment->max_score }}" required>
                            </div>

                            <!-- Field for editing the assessment type -->
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="student-select" {{ $assessment->type == 'student-select' ? 'selected' : '' }}>Student-Select</option>
                                    <option value="teacher-select" {{ $assessment->type == 'teacher-select' ? 'selected' : '' }}>Teacher-Select</option>
                                </select>
                            </div>

                            <!-- Submit button to save the updated assessment details -->
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                        @else
                        <!-- Message indicating the assessment cannot be edited -->
                        <p>This assessment cannot be edited at this time as it has completed reviews.</p>
                        
                        <!-- Button to go back to the normal assessment show page -->
                        <a href="{{ route('assessment.show', $assessment->id) }}" class="btn btn-primary">Back to Assessment</a>
                        @endif
                </div>
            </div>
        </div>
    </div>
@endsection
