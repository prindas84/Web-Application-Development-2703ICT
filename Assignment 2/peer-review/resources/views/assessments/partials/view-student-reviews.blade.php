<div class="accordion mt-3" id="accordionViewReviews">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSubmittedReviews">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSubmittedReviews" aria-expanded="false" aria-controls="collapseSubmittedReviews">
                Reviews Submitted
            </button>
        </h2>
        <div id="collapseSubmittedReviews" class="accordion-collapse collapse" aria-labelledby="headingSubmittedReviews" data-bs-parent="#accordionViewReviews">
            <div class="accordion-body">
                @if($reviewsGiven->isNotEmpty())
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 25%;">Reviewee</th>
                                <th scope="col" style="width: 50%;">Review</th>
                                <th scope="col" style="width: 25%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviewsGiven as $review)
                                <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                                    <td>{{ $review->reviewee->first_name }} {{ $review->reviewee->surname }}</td>
                                    <td>{!! nl2br(e($review->review)) !!}</td>
                                    <td>
                                        <!-- Delete button -->
                                        <form action="{{ route('review.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="border: none; background: none; padding: 0;">
                                                <img src="{{ asset('images/delete.png') }}" alt="Delete Review" title="Delete Review" style="height: 20px; width: 20px;">
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>You haven't given any reviews for this assessment yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="accordion mt-3" id="accordionViewReviews">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingReceivedReviews">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReceivedReviews" aria-expanded="false" aria-controls="collapseReceivedReviews">
                Reviews Received
            </button>
        </h2>
        <div id="collapseReceivedReviews" class="accordion-collapse collapse" aria-labelledby="headingReceivedReviews" data-bs-parent="#accordionViewReviews">
            <div class="accordion-body">
                @if($reviewsReceived->isNotEmpty())
                    <table class="table">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 25%;">Reviewer</th>
                                <th scope="col" style="width: 50%;">Review</th>
                                <th scope="col" style="width: 25%;">Anonymous Feedback</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviewsReceived as $review)
                                <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                                    <td style="padding-top: 12px;">{{ $review->reviewer->first_name }} {{ $review->reviewer->surname }}</td>
                                    <td style="padding-top: 12px;">{!! nl2br(e($review->review)) !!}</td>
                                    <td>
                                        <!-- Feedback buttons -->
                                        @foreach([1 => 'angry.png', 2 => 'sad.png', 3 => 'indifferent.png', 4 => 'happiness.png', 5 => 'smile.png'] as $score => $image)
                                            <form action="{{ route('review.update', $review->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')

                                                <!-- Hidden input to pass the feedback score -->
                                                <input type="hidden" name="feedback_score" value="{{ $score }}">

                                                <button type="submit" style="border: none; background: none; padding: 5px 10px 8px 10px; {{ $review->feedback_score == $score ? 'border: 3px solid green; border-radius: 50px;' : '' }}">
                                                    <img src="{{ asset('images/' . $image) }}" alt="Feedback Score = {{ $score }}" title="Feedback Score = {{ $score }}" style="height: 20px; width: 20px;">
                                                </button>
                                            </form>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>You haven't received any reviews for this assessment yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
