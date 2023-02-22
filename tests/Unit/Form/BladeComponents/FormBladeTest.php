<?php

namespace Tests\Unit\Form\BladeComponents;

use Tests\TestCase;

class FormBladeTest extends TestCase {
    
    /** @test */
    function renders_form_tag_with_slot() {
        $slot = 'This is the slot';
        $this->blade("<x-form.form action='/example'>$slot</x-form.form>")
                ->assertSeeInOrder(['<form', 'action="/example"', '>', 
                    $slot, '</form>'], false);
    }
    
    /** @test */
    function if_no_action_is_given_current_url_is_used() {
        $currentUrl = url()->current();
        $this->blade('<x-form.form></x-form.form>')
                ->assertSeeInOrder(['<form', "action=\"$currentUrl\"", '>'], 
                        false);
    }
    
    /** @test */
    function includes_csrf() {
        $this->blade('<x-form.form></x-form.form>')
                ->assertSee('<input type="hidden" name="_token" value=', false);
    }
    
}
