<?php

$attributes->setAttributes(
        $attributes->only([
                'name',
                'id',
                'value'
        ])->getAttributes()
);
$attributes['type'] = 'hidden';