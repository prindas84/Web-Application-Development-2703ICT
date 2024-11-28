<section>
    <header>
        <h2 class="h5 font-weight-bold text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-muted">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="user_number" :value="__('User Number')" />
            <x-text-input id="user_number" name="user_number" type="text" class="form-control" :value="old('user_number', $user->user_number)" required readonly />
            <x-input-error class="mt-2" :messages="$errors->get('user_number')" />
        </div>

        <div class="mb-3">
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" name="first_name" type="text" class="form-control" :value="old('first_name', $user->first_name)" required autofocus autocomplete="given-name" />
            <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="surname" :value="__('Surname')" />
            <x-text-input id="surname" name="surname" type="text" class="form-control" :value="old('surname', $user->surname)" required autocomplete="family-name" />
            <x-input-error class="mt-2" :messages="$errors->get('surname')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <x-primary-button class="auth-form-button">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-muted mb-0 success_message" 
                   x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)">
                   {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
