<div>
    <form action="{{ route('assessment.groups.assign', ['assessment_id' => $assessment->id]) }}" method="POST" class="d-flex align-items-center">
        @csrf
        <div class="me-3">
            <label for="group_size" style="margin-left: 2px; margin-bottom: 5px;"><strong>Group Size (Minimum):</strong></label>
            <input type="number" class="form-control" id="group_size" name="group_size" value="{{ $assessment->group_size }}" required>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 28px;">Assign Groups</button>
    </form>
</div>