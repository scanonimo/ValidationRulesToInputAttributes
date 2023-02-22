<?php

namespace Tests\Unit\Form;

use App\Classes\Form\Blueprints;
use App\Classes\Form\Controls;
use Tests\TestCase;

class BlueprintsTest extends TestCase {
    
    /** @test */
    function has_both_errorBag_and_controls_properties() {
        $blueprints = new Blueprints;
        $this->assertTrue(property_exists($blueprints, 'errorBag'));
        $this->assertTrue(property_exists($blueprints, 'controls'));
    }
    
    /** @test */
    function controls_property_is_an_instace_of_controls() {
        $blueprints = new Blueprints;
        $this->assertInstanceOf(Controls::class, $blueprints->controls);
    }
    
    /** @test */
    function errorBag_property_must_be_string() {
        $blueprints = new Blueprints;
        $this->assertThrows(
            fn () => $blueprints->errorBag = ['not_a_string'],
            \TypeError::class,
            'of type string'
        );
    }
}
