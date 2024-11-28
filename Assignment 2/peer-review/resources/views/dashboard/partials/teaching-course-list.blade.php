<div class="py-4">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header">
            <h5 class="mb-0">Teaching Courses</h5>
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
            <!-- Check if the faculty member is teaching any courses -->
            @if ($teacherCourses->isNotEmpty())
            
            <!-- Table for displaying the courses the faculty member is teaching -->
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
                    <!-- Loop through each course and display its details -->
                    @foreach ($teacherCourses as $course)
                        <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                            <!-- Course code link -->
                            <td>
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    <strong>{{ $course->course_code }}</strong>
                                </a>
                            </td>
                            <!-- Course name link -->
                            <td>
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    {{ $course->course_name }}
                                </a>
                            </td>
                            <!-- Course description link -->
                            <td>
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    {!! nl2br(e($course->course_description)) !!}
                                </a>
                            </td>
                            <!-- Actions column with buttons to remove, edit, or delete the course -->
                            <td>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    
                                    <!-- Remove Teacher Form (faculty member removes themselves from the course) -->
                                    <form class="remove-teacher" action="{{ route('course.removeTeacher', $course->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                        <button type="submit" style="border: none; background: none; padding: 0;">
                                            <img src="{{ asset('images/remove-user.png') }}" alt="Remove Teacher" title="Remove Teacher" style="height: 20px; width: 20px;">
                                        </button>
                                    </form>

                                    <!-- Edit Course Form (redirects to edit course page) -->
                                    <form action="{{ route('course.edit', $course->id) }}" method="GET">
                                        <button type="submit" style="border: none; background: none; padding: 0;">
                                            <img src="{{ asset('images/pencil.png') }}" alt="Edit Course" title="Edit Course" style="height: 20px; width: 20px;">
                                        </button>
                                    </form>

                                    <!-- Delete Course Form (deletes the course after confirmation) -->
                                    <form action="{{ route('course.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="border: none; background: none; padding: 0;">
                                            <img src="{{ asset('images/delete.png') }}" alt="Delete Course" title="Delete Course" style="height: 20px; width: 20px;">
                                        </button>
                                    </form>
                                </div>  
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Message displayed if the faculty member is not teaching any courses -->
            @else
            <p>You are not teaching any courses at the current time...</p>
            @endif

            <!-- Include a partial view for adding a new course manually or by file -->
            @include('dashboard.partials.add-new-course')
            @include('dashboard.partials.upload-new-course-file')
        </div>
    </div>
</div>
