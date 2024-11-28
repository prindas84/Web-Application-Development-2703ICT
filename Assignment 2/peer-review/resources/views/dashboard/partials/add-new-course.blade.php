<div class="accordion mt-4" id="accordionAddCourse">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingAddCourse">
            <!-- Button to toggle the accordion for adding a course -->
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddCourse" aria-expanded="false" aria-controls="collapseAddCourse">
                Add Course (Manual Upload)
            </button>
        </h2>
        
        <div id="collapseAddCourse" class="accordion-collapse collapse" aria-labelledby="headingAddCourse" data-bs-parent="#accordionAddCourse">
            <div class="accordion-body">
                <!-- Form to add a new course -->
                <form action="{{ route('course.store') }}" method="POST">
                    @csrf <!-- Include CSRF token for form security -->
                    
                    <!-- Pass the user_id (of the faculty member adding the course) as a hidden input -->
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}"> 
                    
                    <!-- Field for entering the course code -->
                    <div class="mb-3">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="course_code" name="course_code" required>
                    </div>
                    
                    <!-- Field for entering the course name -->
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    
                    <!-- Field for entering an optional course description -->
                    <div class="mb-3">
                        <label for="course_description" class="form-label">Course Description</label>
                        <textarea class="form-control" id="course_description" name="course_description"></textarea>
                    </div>
                    
                    <!-- Button to submit the form and add the new course -->
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>
            </div>
        </div>
    </div>
</div>
