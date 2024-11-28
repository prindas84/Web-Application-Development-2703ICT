@extends('layouts.master')

@section('title')
    Register - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="d-flex justify-content-center" style="margin-top: 5%; margin-bottom: 5%;">
        <div class="card" style="width: 400px;">
            <div class="card-body">
                <h5 class="card-title text-center">Register</h5>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- User Number -->
                    <div class="mb-3">
                        <x-input-label for="user_number" :value="__('User Number')" />
                        <x-text-input id="user_number" class="form-control" type="text" name="user_number" :value="old('user_number')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('user_number')" class="mt-2 alert alert-danger register-error" />
                    </div>

                    <!-- First Name -->
                    <div class="mb-3">
                        <x-input-label for="first_name" :value="__('First Name')" />
                        <x-text-input id="first_name" class="form-control" type="text" name="first_name" :value="old('first_name')" required autocomplete="given-name" />
                        <x-input-error :messages="$errors->get('first_name')" class="mt-2 alert alert-danger register-error" />
                    </div>

                    <!-- Surname -->
                    <div class="mb-3">
                        <x-input-label for="surname" :value="__('Surname')" />
                        <x-text-input id="surname" class="form-control" type="text" name="surname" :value="old('surname')" required autocomplete="family-name" />
                        <x-input-error :messages="$errors->get('surname')" class="mt-2 alert alert-danger register-error" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-3">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 alert alert-danger register-error" />
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 alert alert-danger register-error" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 alert alert-danger register-error" />
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a class="text-sm text-secondary" href="{{ route('login') }}" style="color: #505050 !important;">
                            {{ __('Already registered?') }}
                        </a>
                        <x-primary-button class="auth-form-button">
                            {{ __('Register') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
