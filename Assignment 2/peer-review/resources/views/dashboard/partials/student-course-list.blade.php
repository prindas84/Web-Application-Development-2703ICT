<div class="py-4">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header">
            <h5 class="mb-0">Enrolled Course</h5>
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
            <!-- Check if there are any enrolled courses for the student -->
            @if ($studentCourses->isNotEmpty())
            
            <!-- Table for displaying the list of courses the student is enrolled in -->
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 15%;">Course Code</th>
                        <th scope="col" style="width: 25%;">Course Name</th>
                        <th scope="col" style="width: 45%;">Course Description</th>
                        <th scope="col" style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through each enrolled course and display its details -->
                    @foreach ($studentCourses as $course)
                        <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                            <td>
                                <!-- Link to the course details page via the course code -->
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    <strong>{{ $course->course_code }}</strong>
                                </a>
                            </td>
                            <td>
                                <!-- Link to the course details page via the course name -->
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    {{ $course->course_name }}
                                </a>
                            </td>
                            <td>
                                <!-- Link to the course details page via the course description -->
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    {!! nl2br(e($course->course_description)) !!}
                                </a>
                            </td>
                            <td>
                                <!-- Form for the student to unenrol from the course -->
                                <form class="unenroll-student" action="{{ route('course.unenrollStudent', $course->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                    <button type="submit" style="border: none; background: none; padding: 0;">
                                        <img src="{{ asset('images/remove-user.png') }}" alt="Unenrol Student" title="Unenrol Student" style="height: 20px; width: 20px;">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Message displayed if the student is not enrolled in any courses -->
            @else
            <p>You are not enrolled in any courses at the current time...</p>
            @endif
        </div>
    </div>
</div>
