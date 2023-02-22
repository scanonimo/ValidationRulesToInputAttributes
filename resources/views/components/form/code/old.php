<?php

if (isset($attributes['name'])) {
    $errorBag ??= $attributes['errorBag'] ?? 'default';
    if (
            isset($errors) &&
            $errors->hasBag($errorBag) && 
            !is_null($value = old($attributes['name']))
    ) {
        $attributes['value'] = $value;
    }
}