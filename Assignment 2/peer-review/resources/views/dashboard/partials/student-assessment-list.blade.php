<div class="py-4">
    <div class="card shadow-sm rounded-lg">
        <div class="card-header">
            <h5 class="mb-0">Course Assessments</h5>
        </div>
        <div class="card-body">
            <!-- Check if the student has any assigned assessments -->
            @if ($studentAssessments->isNotEmpty())

            <!-- Accordion for Upcoming and Completed Assessments -->
            <div class="accordion mt-4" id="accordionDueAssessments">
                <!-- Accordion for Upcoming Assessments -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingUpcoming">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUpcoming" aria-expanded="false" aria-controls="collapseUpcoming">
                            Upcoming Assessments
                        </button>
                    </h2>
                    <div id="collapseUpcoming" class="accordion-collapse collapse" aria-labelledby="headingUpcoming" data-bs-parent="#accordionAssessments">
                        <div class="accordion-body">
                            @php
                                $upcomingAssessments = $studentAssessments->filter(function($assessment) {
                                    return !$assessment->pivot->complete;
                                });
                            @endphp

                            @if ($upcomingAssessments->isNotEmpty())
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 10%;">Course Code</th>
                                        <th scope="col" style="width: 20%;">Assessment Title</th>
                                        <th scope="col" style="width: 10%;">Due Date</th>
                                        <th scope="col" style="width: 10%;">Due Date</th>
                                        <th scope="col" style="width: 40%;">Assessment Instruction</th>
                                        <th scope="col" style="width: 10%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upcomingAssessments as $assessment)
                                        @php
                                            $isOverdue = \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($assessment->due_date));
                                        @endphp
                                        <tr class="{{ $isOverdue ? 'table-danger' : ($loop->index % 2 === 0 ? 'table-light' : 'table-secondary') }}" 
                                            onclick="window.location='{{ route('assessment.show', $assessment->id) }}';" style="cursor: pointer;">
                                            <td><strong>{{ $assessment->course->course_code }}</strong></td>
                                            <td><strong>{{ $assessment->assessment_title }}</strong></td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($assessment->due_date)->format('d-m-Y') }}
                                                @if($isOverdue)
                                                    <span class="text-danger">(Overdue)</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($assessment->due_time)->format('g:i A') }}</td>
                                            <td>{{ $assessment->assessment_instruction }}</td>
                                            <td>Incomplete</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <p>No upcoming assessments.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accordion for Completed Assessments -->
            <div class="accordion mt-4" id="accordionCompletedAssessments">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCompleted">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompleted" aria-expanded="false" aria-controls="collapseCompleted">
                            Completed Assessments
                        </button>
                    </h2>
                    <div id="collapseCompleted" class="accordion-collapse collapse" aria-labelledby="headingCompleted" data-bs-parent="#accordionAssessments">
                        <div class="accordion-body">
                            @php
                                $completedAssessments = $studentAssessments->filter(function($assessment) {
                                    return $assessment->pivot->complete;
                                });
                            @endphp

                            @if ($completedAssessments->isNotEmpty())
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 10%;">Course Code</th>
                                        <th scope="col" style="width: 20%;">Assessment Title</th>
                                        <th scope="col" style="width: 10%;">Due Date</th>
                                        <th scope="col" style="width: 10%;">Due Date</th>
                                        <th scope="col" style="width: 40%;">Assessment Instruction</th>
                                        <th scope="col" style="width: 10%;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($completedAssessments as $assessment)
                                        <tr class="{{ $loop->index % 2 === 0 ? 'table-light' : 'table-secondary' }}" 
                                            onclick="window.location='{{ route('assessment.show', $assessment->id) }}';" style="cursor: pointer;">
                                            <td><strong>{{ $assessment->course->course_code }}</strong></td>
                                            <td><strong>{{ $assessment->assessment_title }}</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($assessment->due_date)->format('d-m-Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($assessment->due_time)->format('g:i A') }}</td>
                                            <td>{{ $assessment->assessment_instruction }}</td>
                                            <td>Complete</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <p>No completed assessments.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @else
            <p>You have no assigned assessments at the current time...</p>
            @endif
        </div>
    </div>
</div>
