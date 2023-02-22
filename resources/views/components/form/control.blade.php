@include('components.form.code.control')
@props(['label', 'key'])
<div>
    <label for="{{ $attributes['id'] }}">{{ __($label) }}</label>
        <x-form.input {{ $attributes }} />
    <div>
        <label for="{{ $attributes['id'] }}">
            @if(isset($errors) && isset($attributes['name']))
                @error($attributes['name'], $attributes['errorBag'])
                    {{ $message }}
                @enderror
            @endif
        </label>
    </div>
</div>