@extends('layouts.master')

@section('title')
    {{ $course->course_name }} - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="container mt-5 footer-space">
        <div class="py-4">
            <div class="card shadow-sm rounded-lg">
                <div class="card-header">
                    <h5 class="mb-0">{{ $course->course_name }}</h5>
                </div>
                <div class="card-body">
                <!-- Display the course details -->
                <p class="card-text"><strong>Course Code:</strong> {{ $course->course_code }}</p>
                <p class="card-text"><strong>Description:</strong> {!! nl2br(e($course->course_description)) ?? 'No description available' !!}</p>
                <div>
                    <!-- Actions for Faculty -->
                    @if(auth()->user()->role === 'faculty')
                        @if(!$course->isCurrentUserTeacher())
                            <!-- Button for faculty members to start teaching this course -->
                            <form class="add-teacher" action="{{ route('course.addTeacher', $course->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <button type="submit" class="btn btn-primary mt-3">
                                    Teach this Course
                                </button>
                            </form>
                        @else
                            <!-- Notification if the user is currently teaching the course -->
                            <div class="alert mt-3 d-flex justify-content-between align-items-center teacher-notification" role="alert">
                                <p style="margin: 0;">You are currently teaching this course...</p>
                                <div>
                                    <!-- Edit Course Button -->
                                    <a href="{{ route('course.edit', $course->id) }}" class="btn btn-success me-2">
                                        Edit Course
                                    </a>

                                    <!-- Delete Course Button (visible only to authorized users) -->
                                    <form action="{{ route('course.destroy', $course->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this course? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning me-2">
                                            Delete Course
                                        </button>
                                    </form>

                                    <!-- Remove Teacher Button (floated right) -->
                                    <form class="remove-teacher" action="{{ route('course.removeTeacher', $course->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        <button type="submit" class="btn btn-danger">
                                            Leave Teaching Team
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Actions for Students -->
                    @if(auth()->user()->role === 'student')
                        @if(!$course->isCurrentUserStudent())
                            <!-- Button for students to enrol in the course -->
                            <form class="enroll-student" action="{{ route('course.enrollStudent', $course->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                <button type="submit" class="btn btn-success mt-3">
                                    Enrol in Course
                                </button>
                            </form>
                        @else
                            <!-- Success message if the student is already enrolled in the course -->
                            <div class="alert alert-success mt-3 d-flex justify-content-between align-items-center" role="alert">
                                <p style="margin: 0;">You are currently enrolled in this course...</p>
                                <!-- Unenrol Button -->
                                <form class="unenroll-student" action="{{ route('course.unenrollStudent', $course->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <button type="submit" class="btn btn-danger float-end">
                                        Leave Course
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
                <hr>
                
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

                @if($course->isCurrentUserTeacher() || $course->isCurrentUserStudent())
                <!-- Assessments -->
                <div class="accordion mt-4" id="accordionAssessments">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingAssessments">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAssessments" aria-expanded="false" aria-controls="collapseAssessments">
                                Assessments
                            </button>
                        </h2>
                        <div id="collapseAssessments" class="accordion-collapse collapse" aria-labelledby="headingAssessments" data-bs-parent="#accordionAssessments">
                            <div class="accordion-body">
                                <!-- Add Assessment Accordion (Only for Faculty and Teachers) -->
                                @if(auth()->user()->role === 'faculty' && $course->isCurrentUserTeacher())
                                    @include('assessments.partials.add-new-assessment')
                                @endif
                                <!-- Assessment Table -->
                                @if($course->assessments->isNotEmpty())
                                <table class="table mt-4">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 20%;">Assessment Title</th>
                                            <th scope="col" style="width: 20%;">Due Date</th>
                                            <th scope="col" style="width: 40%;">Assessment Instruction</th>
                                            @if(auth()->user()->role === 'faculty' && $course->isCurrentUserTeacher())
                                            <th scope="col" style="width: 20%;">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($course->assessments as $assessment)
                                        <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                                            <td>
                                                <a href="{{ route('assessment.show', $assessment->id) }}" class="table-link">
                                                    <strong>{{ $assessment->assessment_title }}</strong>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('assessment.show', $assessment->id) }}" class="table-link">
                                                    {{ \Carbon\Carbon::parse($assessment->due_date)->format('d-m-Y') }} @ {{ \Carbon\Carbon::parse($assessment->due_time)->format('g:i A') }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('assessment.show', $assessment->id) }}" class="table-link">
                                                    {!! nl2br(e($assessment->assessment_instruction)) !!}
                                                </a>
                                            </td>
                                            @if(auth()->user()->role === 'faculty' && $course->isCurrentUserTeacher())
                                                <td>
                                                    <div style="display: flex; gap: 10px; align-items: center;">
                                                        <!-- Edit Assessment Form -->
                                                        <form action="{{ route('assessment.edit', $assessment->id) }}" method="GET">
                                                            <button type="submit" style="border: none; background: none; padding: 0;">
                                                                <img src="{{ asset('images/pencil.png') }}" alt="Edit Assessment" title="Edit Assessment" style="height: 20px; width: 20px;">
                                                            </button>
                                                        </form>

                                                        <!-- Delete Assessment Form -->
                                                        <form action="{{ route('assessment.destroy', $assessment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assessment?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" style="border: none; background: none; padding: 0;">
                                                                <img src="{{ asset('images/delete.png') }}" alt="Delete Assessment" title="Delete Assessment" style="height: 20px; width: 20px;">
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                    <p>No assessments available for this course.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif


                <!-- Faculty-only section for manual student enrolment -->
                @if(auth()->user()->role === 'faculty' && $course->isCurrentUserTeacher())
                    <div class="accordion mt-4" id="accordionManualEnrollment">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingManualEnrollment">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseManualEnrollment" aria-expanded="false" aria-controls="collapseManualEnrollment">
                                    Manual Enrolment
                                </button>
                            </h2>
                            <div id="collapseManualEnrollment" class="accordion-collapse collapse" aria-labelledby="headingManualEnrollment" data-bs-parent="#accordionManualEnrollment">
                                <div class="accordion-body">
                                    <!-- Form for manually enrolling students in the course -->
                                    <form class="enroll-student" action="{{ route('course.enrollStudent', $course->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <!-- Dropdown list of students who are not enrolled in the course -->
                                            <select class="form-select" name="student_id" required>
                                                <option value="">Select a student to enrol</option>
                                                @foreach($nonEnrolledStudents as $student)
                                                    <option value="{{ $student->id }}">{{ $student->surname }}, {{ $student->first_name }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-primary" type="submit">Enrol</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Accordion for Teachers (collapsed by default) -->
                <div class="accordion mt-4" id="accordionTeachers">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Teaching Staff
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne">
                            <div class="accordion-body">
                                @if($course->teachers->isNotEmpty())
                                    <ul>
                                        @foreach($course->teachers as $teacher)
                                            <li class="d-flex justify-content-between align-items-center">
                                                {{ $teacher->first_name }} {{ $teacher->surname }} ({{ $teacher->email }})
                                                
                                                <!-- Show remove button for faculty who are not the current user -->
                                                @if(auth()->user()->role === 'faculty' && $teacher->id !== auth()->user()->id)
                                                <!-- Remove Teacher Form -->
                                                <form class="remove-teacher" action="{{ route('course.removeTeacher', $course->id) }}" method="POST"  style="margin: 0;">
                                                    @csrf
                                                    <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                                                    <button type="submit" style="border: none; background: none; padding: 0;">
                                                        <img src="{{ asset('images/remove-user.png') }}" alt="Remove Teacher" title="Remove Teacher" style="height: 20px; width: 20px;">
                                                    </button>
                                                </form>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No teaching staff assigned to this course.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accordion for Students (collapsed by default) -->
                <div class="accordion mt-4" id="accordionStudents">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Enrolled Students
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo">
                            <div class="accordion-body">
                                @if($course->students->isNotEmpty())
                                    <ul>
                                        @foreach($course->students as $student)
                                            <li class="d-flex justify-content-between align-items-center">
                                                {{ $student->first_name }} {{ $student->surname }} ({{ $student->email }})

                                                <!-- Faculty can unenrol students from the course -->
                                                @if(auth()->user()->role === 'faculty' && $course->isCurrentUserTeacher())
                                                    <!-- Unenrol Student Form -->
                                                    <form class="unenroll-student" action="{{ route('course.unenrollStudent', $course->id) }}" method="POST" style="margin: 0;">
                                                        @csrf
                                                        <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                        <button type="submit" style="border: none; background: none; padding: 0;">
                                                            <img src="{{ asset('images/remove-user.png') }}" alt="Unenrol Student" title="Unenrol Student" style="height: 20px; width: 20px;">
                                                        </button>
                                                    </form>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No students enrolled in this course.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
