<div>
    @if($reviewsReceived->isNotEmpty())
        <table class="table">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 20%;">Reviewer</th>
                    <th scope="col" style="width: 80%;">Review</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviewsReceived as $review)
                    <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                        <td>{{ $review->reviewer->first_name }} {{ $review->reviewer->surname }}</td>
                        <td>{!! nl2br(e($review->review)) !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No reviews received for this assessment.</p>
    @endif
</div>