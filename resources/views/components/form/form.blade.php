@props(['action' => url()->current()])
<form method="post" action="{{ $action }}">
    @csrf
    {{ $slot }}
</form>