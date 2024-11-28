@if($paginatedGroupList->isNotEmpty())
    <table class="table">
        <thead class="table-light">
            <tr>
                <th scope="col" style="width: 10%;">Group Number</th>
                <th scope="col" style="width: 20%;">Student Name</th>
                <th scope="col" style="width: 15%;">Reviews Submitted</th>
                <th scope="col" style="width: 15%;">Reviews Received</th>
                <th scope="col" style="width: 10%;">Score</th>
                <th scope="col" style="width: 20%;">Assessment Status</th>
                <th scope="col" style="width: 10%;">Marked</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginatedGroupList as $group_number => $group_students)
                <!-- First row with the group number and the first student -->
                <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                    <!-- Group number with rowspan equal to the number of students in the group -->
                    <td rowspan="{{ count($group_students) }}">
                        <strong>Group {{ $group_number + $pageOffset + 1 }}</strong>
                    </td>
                    <td>
                        <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[0]['student']['id']]) }}" class="table-link">
                            {{ $group_students[0]['student']['first_name'] }} {{ $group_students[0]['student']['surname'] }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[0]['student']['id']]) }}" class="table-link">
                            {{ $group_students[0]['student']['reviews_submitted'] }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[0]['student']['id']]) }}" class="table-link">
                            {{ $group_students[0]['student']['reviews_received'] }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[0]['student']['id']]) }}" class="table-link">
                        {{ $group_students[0]['student']['score'] == -1 ? 0 : $group_students[0]['student']['score']}}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[0]['student']['id']]) }}" class="table-link">
                            {{ $group_students[0]['student']['is_complete'] }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[0]['student']['id']]) }}" class="table-link">
                            {{ $group_students[0]['student']['score'] == -1 ? "Not Marked" : "Marked"}}
                        </a>
                    </td>
                </tr>

                <!-- Loop through the remaining students in the group, if any -->
                @for ($i = 1; $i < count($group_students); $i++)
                    <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}">
                        <td>
                            <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[$i]['student']['id']]) }}" class="table-link">
                                {{ $group_students[$i]['student']['first_name'] }} {{ $group_students[$i]['student']['surname'] }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[$i]['student']['id']]) }}" class="table-link">
                                {{ $group_students[$i]['student']['reviews_submitted'] }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[$i]['student']['id']]) }}" class="table-link">
                                {{ $group_students[$i]['student']['reviews_received'] }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[$i]['student']['id']]) }}" class="table-link">
                                {{ $group_students[$i]['student']['score'] == -1 ? 0 : $group_students[$i]['student']['score'] }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[$i]['student']['id']]) }}" class="table-link">
                                {{ $group_students[$i]['student']['is_complete'] }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('student.assessment.show', ['assessment_id' => $assessment->id, 'student_id' => $group_students[0]['student']['id']]) }}" class="table-link">
                                {{ $group_students[$i]['student']['score'] == -1 ? "Not Marked" : "Marked"}}
                            </a>
                        </td>
                    </tr>
                @endfor
            @endforeach
        </tbody>
    </table>
@else
    <p>No groups found for this assessment.</p>
@endif

<!-- Pagination Links -->
@if($totalGroupPages > 1)
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
            @for($page = max(1, $currentPage - 2); $page <= min($totalGroupPages, $currentPage + 2); $page++)
                <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page]) }}">{{ $page }}</a>
                </li>
            @endfor

            <!-- Next page link -->
            @if($currentPage < $totalGroupPages)
                <li class="page-item">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">Next</a>
                </li>
            @endif

            <!-- Last page link -->
            @if($currentPage < $totalGroupPages)
                <li class="page-item">
                    <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $totalGroupPages]) }}">Last</a>
                </li>
            @endif
        </ul>
    </nav>
@endif
