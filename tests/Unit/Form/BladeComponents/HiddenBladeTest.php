<?php

namespace Tests\Unit\Form\BladeComponents;

use Tests\TestCase;

class HiddenBladeTest extends TestCase {
    
    /** @test */
    function renders_an_input_with_a_hidden_type_attribute() {
        $attributes = 'name="id" value="50"';
        $this->blade("<x-form.hidden $attributes/>")
                ->assertSee("<input $attributes type=\"hidden\">", false);
    }
    
    /** @test */
    function only_attributes_name_id_and_value_are_keept() {
        $attributesGiven = 'name="id" id="id" value="50" type="text" '
                . 'minlength="3" maxlength="60"';
        $attrbutesExpected = 'name="id" id="id" value="50" type="hidden"';
        $this->blade("<x-form.hidden $attributesGiven/>")
                ->assertSee("<input $attrbutesExpected>", false);        
    }
}
