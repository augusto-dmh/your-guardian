<section>
    <header>
        <h2 class="text-lg font-medium">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <x-form.label for="first_name" :value="__('First Name')" />

            <x-form.input id="first_name" name="first_name" type="text" class="block w-full" :value="old('first_name', $user->first_name)"
                required autofocus autocomplete="first_name" />

            <x-form.error :messages="$errors->get('first_name')" />
        </div>

        <div class="space-y-2">
            <x-form.label for="last_name" :value="__('Last Name')" />

            <x-form.input id="last_name" name="last_name" type="text" class="block w-full" :value="old('last_name', $user->last_name)" required
                autofocus autocomplete="last_name" />

            <x-form.error :messages="$errors->get('last_name')" />
        </div>

        <div class="space-y-2">
            <x-form.label for="birthdate" :value="__('Birthdate')" />

            <x-form.input id="birthdate" name="birthdate" type="date" class="block w-full" :value="old('birthdate', $user->birthdate)" required
                autofocus autocomplete="birthdate" />

            <x-form.error :messages="$errors->get('birthdate')" />
        </div>

        <div class="space-y-2">
            <x-form.label for="email" :value="__('Email')" />

            <x-form.input id="email" name="email" type="email" class="block w-full" :value="old('email', $user->email)" required
                autocomplete="email" />

            <x-form.error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-300">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="text-sm text-gray-400 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hover:text-gray-200 focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-button>
                {{ __('Save') }}
            </x-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-400">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
