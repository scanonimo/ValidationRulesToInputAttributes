<x-auth-layout title="{{ __('auth/login.login') }}">
    <x-form.builder :blueprints=$blueprints>
        <div class="card-actions justify-end items-center mt-6">

            <button class="btn btn-primary">{{ __('auth/login.login') }}</button>
        </div>
    </x-form.builder>
</x-auth-layout>