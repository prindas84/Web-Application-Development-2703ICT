@extends('layouts.master')

@section('title')
    Edit Course - {{ $course->course_code }} - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="container mt-5">
        <div class="card shadow-sm rounded-lg footer-space">
            <div class="card-header">
                <h5 class="mb-0">Edit Course: {{ $course->course_name }}</h5>
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
                
                <!-- Form to edit the course details -->
                <form action="{{ route('course.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Specify that this form will use the PUT method for updating the course -->

                    <!-- Field for editing the course code -->
                    <div class="mb-3">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="course_code" name="course_code" value="{{ $course->course_code }}" required>
                    </div>

                    <!-- Field for editing the course name -->
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" value="{{ $course->course_name }}" required>
                    </div>

                    <!-- Field for editing the course description -->
                    <div class="mb-3">
                        <label for="course_description" class="form-label">Course Description</label>
                        <textarea class="form-control" id="course_description" name="course_description" rows="8" >{{ $course->course_description }}</textarea>
                    </div>
                    
                    <!-- Submit button to save the updated course details -->
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
