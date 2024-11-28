@if($paginatedStudentList->isNotEmpty())
    <table class="table">
        <thead class="table-light">
            <tr>
                <th scope="col" style="width: 20%;">Student Name</th>
                <th scope="col" style="width: 15%;">Reviews Submitted</th>
                <th scope="col" style="width: 15%;">Reviews Received</th>
                <th scope="col" style="width: 15%;">Score</th>
                <th scope="col" style="width: 20%;">Assessment Status</th>
                <th scope="col" style="width: 15%;">Marked</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paginatedStudentList as $student)
                <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                    <td><a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $student['id']]) }}" class="table-link">{{ $student['name'] }}</a></td>
                    <td><a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $student['id']]) }}" class="table-link">{{ $student['reviews_submitted'] }}</a></td>
                    <td><a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $student['id']]) }}" class="table-link">{{ $student['reviews_received'] }}</a></td>
                    <td><a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $student['id']]) }}" class="table-link">{{ $student['score'] == -1 ? 0 : $student['score'] }}</a></td>
                    <td><a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $student['id']]) }}" class="table-link">{{ $student['is_complete'] }}</a></td>
                    <td><a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $student['id']]) }}" class="table-link">{{ $student['score'] == -1 ? "Not Marked" : "Marked" }}</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No students found for this assessment.</p>
@endif

<!-- Pagination Links -->
@if($totalPages > 1)
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <!-- First page link -->
            @if($currentPage > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">First</a>
                </li>
            @endif

            <!-- Previous page link -->
            @if($currentPage > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">Previous</a>
                </li>
            @endif

            <!-- Page number links -->
            @for($page = max(1, $currentPage - 2); $page <= min($totalPages, $currentPage + 2); $page++)
                <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page]) }}">{{ $page }}</a>
                </li>
            @endfor

            <!-- Next page link -->
            @if($currentPage < $totalPages)
                <li class="page-item">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">Next</a>
                </li>
            @endif

            <!-- Last page link -->
            @if($currentPage < $totalPages)
                <li class="page-item">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $totalPages]) }}">Last</a>
                </li>
            @endif
        </ul>
    </nav>
@endif

