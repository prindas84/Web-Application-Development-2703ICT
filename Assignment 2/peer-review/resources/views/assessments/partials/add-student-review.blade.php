<div class="accordion mt-3" id="accordionAddReview">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingAddReview">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddReview" aria-expanded="false" aria-controls="collapseAddReview">
                Add Review
            </button>
        </h2>
        <div id="collapseAddReview" class="accordion-collapse collapse" aria-labelledby="headingAddReview" data-bs-parent="#accordionAddReview">
            <div class="accordion-body">
                <!-- Form to add a new review -->
                <form action="{{ route('review.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">
                    <input type="hidden" name="reviewer_id" value="{{ auth()->user()->id }}">
                    
                    <div class="mb-3">
                        <label for="reviewee_id" class="form-label">Reviewee</label>
                        @if($assessment->type === 'student-select')
                            <select class="form-select" id="reviewee_id" name="reviewee_id">
                                <option value="">Select Student</option>
                                @foreach($course->students as $student)
                                    @php
                                        // Check if this student has already received a review for this assessment
                                        $hasReview = $assessment->reviews->where('reviewee_id', $student->id)->isNotEmpty();
                                    @endphp
                                    @if(!$hasReview && $student->id !== auth()->user()->id)
                                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->surname }}</option>
                                    @endif
                                @endforeach
                            </select>
                        @else
                            <select class="form-select" id="reviewee_id" name="reviewee_id">
                                <option value="">Select Student</option>
                                @php
                                    // Fetch the current user's group
                                    $userGroupNumber = $assessment->groups->where('user_id', auth()->user()->id)->first()->group_number ?? null;
                                @endphp
                                @if($userGroupNumber)
                                    @foreach($course->students as $student)
                                        @php
                                            // Check if the student is in the same group as the logged-in user
                                            $isInSameGroup = $assessment->groups->where('user_id', $student->id)->where('group_number', $userGroupNumber)->isNotEmpty();
                                            // Check if this student has already received a review for this assessment
                                            $hasReview = $assessment->reviews->where('reviewee_id', $student->id)->isNotEmpty();
                                        @endphp
                                        @if($isInSameGroup && !$hasReview && $student->id !== auth()->user()->id)
                                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->surname }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        @endif

                    </div>

                    <div class="mb-3">
                        <label for="review" class="form-label">Review</label>
                        <textarea class="form-control" id="review" name="review" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
                @if($reviewAverage >= 4)
                    <div class="alert alert-success" style="margin-top: 20px;">
                        <p style="margin-bottom: 0px;">Your Review Score Average is {{ $reviewAverage }} Stars.</p>
                        <p style="margin-bottom: 0px;"><strong>GREAT JOB, KEEP IT UP!!!</strong></p>
                    </div>
                @elseif($reviewAverage >= 2.5)
                    <div class="alert alert-info" style="margin-top: 20px;">
                        <p style="padding-bottom: 0px;">Your Review Score Average is {{ $reviewAverage }} Stars.</p>
                        <p style="margin-bottom: 0px;"><strong>CAN YOU DO BETTER??? WE THINK YOU PROBABLY CAN!!!</strong></p>
                    </div>
                @elseif($reviewAverage > 0)
                    <div class="alert alert-danger" style="margin-top: 20px;">
                        <p style="padding-bottom: 0px;">Your Review Score Average is {{ $reviewAverage }} Stars.</p>
                        <p style="margin-bottom: 0px;"><strong>NOT GOOD ENOUGH... YOU NEED TO MAKE MORE OF AN EFFORT!!!</strong></p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

