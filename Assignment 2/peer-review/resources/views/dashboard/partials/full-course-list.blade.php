<div class="py-4">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header">
            <h5 class="mb-0">Full Course List</h5>
        </div>
        <div class="card-body">
            <!-- Table for displaying the list of courses -->
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
                    @foreach ($courses as $course)
                        <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                            <td>
                                <!-- Link to the course details page -->
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    <strong>{{ $course->course_code }}</strong>
                                </a>
                            </td>
                            <td>
                                <!-- Link to the course name -->
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    {{ $course->course_name }}
                                </a>
                            </td>
                            <td>
                                <!-- Link to the course description -->
                                <a href="{{ route('course.show', $course->id) }}" class="table-link">
                                    {!! nl2br(e($course->course_description)) !!}
                                </a>
                            </td>
                            <td>
                                <!-- Display actions for faculty members -->
                                @if(auth()->user()->role === 'faculty')
                                    @if(!$course->isCurrentUserTeacher())
                                        <!-- Add Teacher Form for faculty members who are not yet teaching this course -->
                                        <form class="add-teacher" action="{{ route('course.addTeacher', $course->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <button type="submit" style="border: none; background: none; padding: 0;">
                                                <img src="{{ asset('images/add-user.png') }}" alt="Add Teacher" title="Add Teacher" style="height: 20px; width: 20px;">
                                            </button>
                                        </form>
                                    @else
                                        <!-- Remove Teacher Form for faculty members already teaching the course -->
                                        <form class="remove-teacher" action="{{ route('course.removeTeacher', $course->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <button type="submit" style="border: none; background: none; padding: 0;">
                                                <img src="{{ asset('images/remove-user.png') }}" alt="Remove Teacher" title="Remove Teacher" style="height: 20px; width: 20px;">
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                
                                <!-- Display actions for students -->
                                @if(auth()->user()->role === 'student')
                                    @if(!$course->isCurrentUserStudent())
                                        <!-- Enrolment form for students not yet enrolled in the course -->
                                        <form class="enroll-student" action="{{ route('course.enrollStudent', $course->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <button type="submit" style="border: none; background: none; padding: 0;">
                                                <img src="{{ asset('images/add-user.png') }}" alt="Enroll Student" title="Enroll Student" style="height: 20px; width: 20px;">
                                            </button>
                                        </form>
                                    @else
                                        <!-- Unenrolment form for students already enrolled in the course -->
                                        <form class="unenroll-student" action="{{ route('course.unenrollStudent', $course->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                                            <button type="submit" style="border: none; background: none; padding: 0;">
                                                <img src="{{ asset('images/remove-user.png') }}" alt="Unenroll Student" title="Unenroll Student" style="height: 20px; width: 20px;">
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
