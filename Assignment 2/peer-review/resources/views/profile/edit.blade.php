@extends('layouts.master')

@section('title')
    Profile - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <!-- Container for profile page content -->
    <div class="container mt-5 footer-space">
        <!-- Profile page heading -->
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
            {{ __('Profile') }}
        </h2>

        <!-- Grid layout for profile sections (Update Profile, Update Password, Delete Account) -->
        <div class="row g-4">
            
            <!-- Section for updating profile information -->
            <div class="col-md-12">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Update Profile Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Include the form for updating profile information -->
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Section for updating password -->
            <div class="col-md-12">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Update Password') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Include the form for updating password -->
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Section for deleting the account -->
            <div class="col-md-12">
                <div class="card shadow-sm rounded-lg">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Delete Account') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Include the form for deleting the account -->
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
