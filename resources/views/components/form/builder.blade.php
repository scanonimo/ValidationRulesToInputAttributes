@include('components.form.code.builder')
@props([ 'controls', 'action' => null ])
<x-form.form :action=$action>
@php
    $attrs = (new Illuminate\View\ComponentAttributeBag());
@endphp
@foreach($controls as $key => $control)
    @php
        $attrs->setAttributes(array_merge($attributes->getAttributes(), 
                        $control->all()));
    @endphp
    @if(isset($control['type']) && $control['type'] == 'hidden')
        <x-form.hidden :attributes=$attrs/>
    @else
        <x-form.control :attributes=$attrs/>
    @endif
@endforeach
{{ $slot }}
</x-form.form>
