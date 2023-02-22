@props(['title' => null])
<x-general-layout :title="$title">
    <div class="min-h-screen bg-base-200 flex flex-col items-center pt-6 pb-8">
        <div class="mb-5">
            <a href="/"  class="w-20 h-20 block">
                <x-application-logo/>
            </a>
        </div>
        <div class="card shadow-xl bg-base-100 w-full max-w-md">
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-general-layout>