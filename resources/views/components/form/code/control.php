<?php

if (!isset($attributes['name']) && !isset($attributes['id']) &&
        !isset($attributes['key'])) {
    throw new InvalidArgumentException(__('errors.control_minimum_requirements',
                            ['control' => json_encode($attributes
                                                ->getAttributes())]));
}

$attributes['key'] ??= $attributes['name'] ?? $attributes['id'];
$attributes['id'] ??= $attributes['name'] ?? $attributes['key'];
$attributes['errorBag'] ??= 'default';

if ($attributes['errorBag'] !== 'default') {
    $attributes['id'] .= '_' . $attributes['errorBag'];
}

$attributes['label'] ??= __("validation.attributes.{$attributes['key']}");
