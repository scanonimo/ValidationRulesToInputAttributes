<x-auth-layout title="{{ __('auth/signup.signup') }}">
    <x-form.builder :blueprints=$blueprints>
        <div class="card-actions justify-end items-center mt-6">
            <a class="link link-primary mr-3" href="/login">
                {{ __('auth/signup.already-registered') }}
            </a>
            
            <button class="btn btn-primary">{{ __('auth/signup.signup') }}</button>
        </div>
    </x-form.builder>
</x-auth-layout>