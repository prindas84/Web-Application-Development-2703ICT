<div>
    @if($reviewsGiven->isNotEmpty())
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 20%;">Reviewee</th>
                    <th scope="col" style="width: 80%;">Review</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviewsGiven as $review)
                    <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                        <td>{{ $review->reviewee->first_name }} {{ $review->reviewee->surname }}</td>
                        <td>{!! nl2br(e($review->review)) !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No reviews given for this assessment.</p>
    @endif
</div>
