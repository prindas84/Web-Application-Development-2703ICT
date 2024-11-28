@extends('layouts.master')

@section('title')
    Agency Details - WAD Assignment 1 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="container">
        <div class="page-header montserrat-heading">
            <h2>Agency Details</h2>
        </div>

        <!-- Display agency details -->
        <div class="clearfix agency-content">
            <div>
                <div class="mb-3">
                    <h4>Agency:</h4>
                    <p>{{ $agency->agency_name }}</p>
                </div>

                <div class="mb-3">
                    <h4>Address:</h4>
                    <p>{{ $agency->agency_address }}</p>
                </div>
            </div>
        </div>

        <!-- List of agents associated with this agency -->
        <div class="agent-list mt-4">
            <h4>Agents List</h4>

            <!-- Check if there are any agents for the agency -->
            @if(!empty($agents))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Surname</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Review Count</th>
                            <th>Average Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through the agents and display their details -->
                        @foreach ($agents as $agent)
                            <tr onclick="window.location='{{ route('view-agent', ['agentNumber' => $agent->id]) }}'" style="cursor:pointer;">
                                <td>{{ $agent->first_name }}</td>
                                <td>{{ $agent->surname }}</td>
                                <td>{{ $agent->email }}</td>
                                <td>{{ $agent->phone }}</td>
                                <!-- Handle case where no reviews are recorded for the agent -->
                                @if(is_null($agent->average_rating))
                                    <td colspan="2">NO REVIEWS RECORDED</td>
                                @else
                                    <td>{{ $agent->review_count }}</td>
                                    <td>{{ number_format($agent->average_rating, 1) }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <!-- Display message if no agents are found for the agency -->
                <p>No agents found for this agency.</p>
            @endif
        </div>
    </div>
@endsection
