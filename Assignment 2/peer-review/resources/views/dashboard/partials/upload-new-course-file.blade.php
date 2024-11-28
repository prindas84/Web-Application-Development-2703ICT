<div class="accordion mt-4" id="accordionUploadCourse">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingUploadCourse">
            <!-- Button to toggle the accordion for uploading a course file -->
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploadCourse" aria-expanded="false" aria-controls="collapseUploadCourse">
                Add Course (File Upload)
            </button>
        </h2>
        
        <div id="collapseUploadCourse" class="accordion-collapse collapse" aria-labelledby="headingUploadCourse" data-bs-parent="#accordionUploadCourse">
            <div class="accordion-body">
                <!-- Form to upload the course file -->
                <form action="{{ route('course.uploadCourseData') }}" method="POST" enctype="multipart/form-data">
                    @csrf <!-- Include CSRF token for form security -->

                    <!-- Hidden field to pass the user_id of the faculty member uploading the file -->
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}"> 

                    <!-- File input for selecting the TXT file -->
                    <div class="mb-3">
                        <input type="file" class="form-control" id="course_file" name="course_file" required>
                        <p style="font-size:10px;font-size: 12px;margin-top: 5px;margin-left: 2px;">Select a file to upload (.txt files only).</p>
                    </div>
                    
                    <!-- Button to submit the form and upload the file -->
                    <button type="submit" class="btn btn-primary">Upload Course Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
