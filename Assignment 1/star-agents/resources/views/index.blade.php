@extends('layouts.master')

@section('title')
    Homepage - WAD Assignment 1 (Matthew Prendergast: s5283740)
@endsection

@section('banner')
    <!-- Banner section with an image -->
    <div class="banner-container">
        <img src="{{ asset('images/banner.jpg') }}" alt="Banner Image" class="img-fluid w-100">
    </div>
@endsection

@section('content')
    <div class="container page-content">
        <div class="page-header montserrat-heading">
            <h2>Rate Your Local Agent</h2>
        </div>

        <!-- Static content -->
        <div>
            <p>Lorem ipsum dolor sit amet, ea meis numquam repudiare pro. His ei audire omnesque consulatu, has modo
                volutpat consulatu in, te fugit aeterno definitiones vix. Vis ullum numquam at, et adhuc atqui congue pri.
                No tale copiosae mea. Te principes corrumpit sit, at modus accusamus quaerendum nec. Nam eu aperiam
                mnesarchum, alii apeirian appellantur ea per. An sea eius utamur laboramus. Mel malorum detraxit adipisci
                an, rebum tibique consequat in eum. Eum at aliquando efficiantur reprehendunt, an accusam senserit
                dissentias sit, at alterum dolorum sadipscing ius. Dicit feugiat legimus eos ea, mel dolorem accommodare
                signiferumque ei, dicit reprimique usu et.</p>
            <p>Epicuri neglegentur interpretaris pro eu. Tritani temporibus usu id, eum id luptatum invenire. Platonem
                salutandi est no, cum ea putent feugait. Ne lorem neglegentur per, sed ex dolorum recusabo. Eam nonumy
                putent ea, duo ei verear luptatum, eam id nihil docendi ancillae. Mea te dico aperiam omittantur, sea an
                liber dolor ocurreret. Eu invenire elaboraret consectetuer mel. Idque ignota intellegam eam ei. Est at
                commodo lobortis. Minimum nominavi delectus no nam, cu legere cetero vix. Graeco antiopam ne eos, ea nostro
                accumsan interesset eos. At partiendo neglegentur his, in enim ponderum vel, ius ei fabellas iudicabit
                adipiscing. In oratio mandamus his, has causae oporteat ei.</p>
        </div>

        <!-- Agent information and sorting options -->
        <div class="mt-4" id="agent-listings">
            <h3>Agent Information</h3>
            <form method="GET" action="{{ route('home') }}" id="orderForm">
                <!-- Dropdown for sorting agents based on different criteria -->
                <label for="order">Order By:</label>
                <select name="order" id="order" onchange="submitFormWithFragment()">
                    <option value="default" {{ request('order') == 'default' ? 'selected' : '' }}>Order by Agent Name - Ascending</option>
                    <option value="default_desc" {{ request('order') == 'default_desc' ? 'selected' : '' }}>Order by Agent Name - Descending</option>
                    <option value="review_count" {{ request('order') == 'review_count' ? 'selected' : '' }}>Order by Review Count - Ascending</option>
                    <option value="review_count_desc" {{ request('order') == 'review_count_desc' ? 'selected' : '' }}>Order by Review Count - Descending</option>
                    <option value="review_average" {{ request('order') == 'review_average' ? 'selected' : '' }}>Order by Review Average - Ascending</option>
                    <option value="review_average_desc" {{ request('order') == 'review_average_desc' ? 'selected' : '' }}>Order by Review Average - Descending</option>
                </select>
            </form>
            <script>
                function submitFormWithFragment() {
                    // Submit the form and append the #agent-listings fragment to the URL
                    var form = document.getElementById('orderForm');
                    form.action = form.action + "#agent-listings";
                    form.submit();
                }
            </script>

            <!-- Table to display agent information -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Agency Name</th>
                        <th>Agency Address</th>
                        <th>Review Count</th>
                        <th>Average Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through agent data and display each agent's details -->
                    @foreach($data as $record)
                    <tr onclick="window.location='{{ route('view-agent', $record->id) }}'" style="cursor:pointer;">
                        <td>{{ $record->first_name }}</td>
                        <td>{{ $record->surname }}</td>
                        <td>{{ $record->email }}</td>
                        <td>{{ $record->phone }}</td>
                        <td>{{ $record->agency_name }}</td>
                        <td>{{ $record->agency_address }}</td>
                        <!-- Display message if no reviews are recorded -->
                        @if(is_null($record->average_rating))
                            <td colspan="2">NO REVIEWS RECORDED</td>
                        @else
                            <td>{{ $record->review_count }}</td>
                            <td>{{ number_format($record->average_rating, 1) }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
