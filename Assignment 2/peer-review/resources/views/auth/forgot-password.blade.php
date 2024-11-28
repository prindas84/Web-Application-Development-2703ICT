@extends('layouts.master')

@section('title')
    Password Reset - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="d-flex justify-content-center" style="margin-top: 10%; margin-bottom: 10%;">
        <div class="card" style="width: 600px;">
            <div class="card-body">
                <h5 class="card-title text-center">Reset Password</h5>

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <x-primary-button class="auth-form-button">
                            {{ __('Email Password Reset Link') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
