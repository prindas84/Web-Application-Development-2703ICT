<div style="margin-left: 2px;">
    <p><strong>Current Score:</strong> {{ $studentAssessment->pivot->score == -1 ? 0 : $studentAssessment->pivot->score }}</p>
    <p><strong>Maximum Score:</strong> {{ $assessment->max_score }}</p>
</div>
<div>
    <form action="{{ route('student.assessment.updateScore', ['assessment_id' => $assessment->id, 'student_id' => $student->id]) }}" method="POST" class="d-flex align-items-center">
        @csrf
        @method('PUT')

        <div class="me-3">
            <input type="number" class="form-control" id="score" name="score" value="{{ $studentAssessment->pivot->score == -1 ? 0 : $studentAssessment->pivot->score }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Score</button>
    </form>
</div>