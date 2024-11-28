<div class="accordion mt-3" id="accordionAddAssessment">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingAddAssessment">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddAssessment" aria-expanded="false" aria-controls="collapseAddAssessment">
                Add Assessment
            </button>
        </h2>
        <div id="collapseAddAssessment" class="accordion-collapse collapse" aria-labelledby="headingAddAssessment" data-bs-parent="#accordionAddAssessment">
            <div class="accordion-body">
                <!-- Form to add a new assessment -->
                <form action="{{ route('assessment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <div class="mb-3">
                        <label for="assessment_title" class="form-label">Assessment Title</label>
                        <input type="text" class="form-control" id="assessment_title" name="assessment_title" required>
                    </div>
                    <div class="mb-3">
                        <label for="assessment_instruction" class="form-label">Assessment Instruction</label>
                        <textarea class="form-control" id="assessment_instruction" name="assessment_instruction" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="due_time" class="form-label">Due Time</label>
                        <input type="time" class="form-control" id="due_time" name="due_time" required>
                    </div>
                    <div class="mb-3">
                        <label for="reviews_required" class="form-label">Reviews Required</label>
                        <input type="number" class="form-control" id="reviews_required" name="reviews_required" required>
                    </div>
                    <div class="mb-3">
                        <label for="max_score" class="form-label">Max Score</label>
                        <input type="number" class="form-control" id="max_score" name="max_score" value="100" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="student-select">Student-Select</option>
                            <option value="teacher-select">Teacher-Select</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Assessment</button>
                </form>
            </div>
        </div>
    </div>
</div>