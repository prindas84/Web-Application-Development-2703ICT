@extends('layouts.master')

@section('title')
    Login - WAD Assignment 2 (Matthew Prendergast: s5283740)
@endsection

@section('content')
    <div class="d-flex justify-content-center" style="margin-top: 7%; margin-bottom: 7%;">
        <div class="card" style="width: 400px;">
            <div class="card-body">
                <h5 class="card-title text-center">Login</h5>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <!-- User Number -->
                    <div class="mb-3">
                        <x-input-label for="user_number" :value="__('User Number')" />
                        <x-text-input id="user_number" class="form-control" type="text" name="user_number" :value="old('user_number')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('user_number')" class="mt-2" />
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <!-- Remember Me -->
                    <div class="form-check mb-3">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                    </div>
                    <div class="d-flex justify-content-between">
                        @if (Route::has('password.request'))
                            <a class="text-sm text-secondary" href="{{ route('password.request') }}" style="color: #505050 !important;">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                        <x-primary-button class="auth-form-button">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
