@extends('layouts.master')

@section('title')
    Dashboard - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <!-- Container for the dashboard content -->
    <div class="container mt-5 footer-space">
        <!-- Heading for the dashboard page -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        
        <!-- Include teaching courses list if the user is a faculty member -->
        @if (auth()->user()->role === 'faculty')
            @include('dashboard.partials.teaching-course-list')
        @endif

        <!-- Include student courses list if the user is a student -->
        @if (auth()->user()->role === 'student')
            @include('dashboard.partials.student-course-list')
            @include('dashboard.partials.student-assessment-list')
        @endif

        <!-- Include the full course list (accessible to both faculty and students) -->
        @include('dashboard.partials.full-course-list')
    </div>
@endsection
