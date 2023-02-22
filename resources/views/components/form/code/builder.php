<?php

use App\Classes\Form\Blueprints;

validator($attributes->getAttributes(), [
    'blueprints' => ['required']
])->validate();

if (!($attributes['blueprints'] instanceof Blueprints)) {
    throw new InvalidArgumentException(__('errors.wrong_instace', [
                        'attribute' => 'blueprints',
                        'class' => Blueprints::class
    ]));
}

$attributes['errorBag'] = $attributes['blueprints']->errorBag;
$attributes['controls'] = $attributes['blueprints']->controls;
unset($attributes['blueprints']);