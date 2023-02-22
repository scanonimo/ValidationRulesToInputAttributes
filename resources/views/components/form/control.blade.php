@include('components.form.code.control')
@props(['label', 'key'])
<div class="form-control">
    <label class="label label-text mt-2" for="{{ $attributes['id'] }}">
        {{ __($label) }}
    </label>
        <x-form.input {{ $attributes }} />
    <div class="label py-0">
        <label 
            class="text-error label-text animate-wiggle" 
            for="{{ $attributes['id'] }}"
        >
            @if(isset($errors) && isset($attributes['name']))
                @error($attributes['name'], $attributes['errorBag'])
                    {{ $message }}
                @enderror
            @endif
        </label>
    </div>
</div>