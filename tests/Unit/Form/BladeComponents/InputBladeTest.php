<?php

namespace Tests\Unit\Form\BladeComponents;

use Tests\TestCase;

class InputBladeTest extends TestCase {
    
    public function setUp(): void {
        parent::setUp();
        request()->setLaravelSession(session());
    }

    /** @test */
    function renders_an_input_with_the_given_attributes() {
        $attributes = 'name="username" type="text" minlength="3" maxlength="60"';
        $this->blade("<x-form.input $attributes/>")
                ->assertSeeInOrder(['<input', $attributes, '>'], false);
    }

    /** @test */
    function errorBag_attribute_wont_be_render() {
        $this->blade('<x-form.input errorBag="example"/>')
                ->assertDontSee('errorBag="example"', false);
    }

    /** @test */
    function old_input_value_is_used_if_error_bags_match() {
        $this->withViewErrors(
                        ['password' =>
                            'Some non username error but in the same error bag'],
                        'sameBag')
                ->withSession(['_old_input.username' => 'old.value'])
                ->blade('<x-form.input name="username" errorBag="sameBag"/>')
                ->assertSee('value="old.value"', false);
    }

    /** @test */
    function if_no_error_bag_is_given_default_is_used() {
        $this->withViewErrors(['password' => 'Some error in default error bag'])
                ->withSession(['_old_input.username' => 'old.value'])
                ->blade('<x-form.input name="username"/>')
                ->assertSee('value="old.value"', false);
    }

    /** @test */
    function if_error_bags_dont_match_old_input_value_wont_be_used() {
        $this->withViewErrors(
                        ['password' => 'Some error in a diferent error bag'],
                        'notTheSame'
                )
                ->withSession(['_old_input.username' => 'old.value'])
                ->blade('<x-form.input name="username" errorBag="diferent"/>')
                ->assertDontSee('value="old.value"', false);
    }
    
    /** @test */
    function to_use_old_input_value_name_attribute_must_be_given() {
        $this->withViewErrors(['password' => 'Some error in default error bag'])
                ->withSession(['_old_input.username' => 'old.value'])
                ->blade('<x-form.input id="username"/>')
                ->assertDontSee('value="old.value"', false);
    }

    /** @test */
    function to_use_old_input_value_session_must_have_errors() {
        $this->withSession(['_old_input.username' => 'old.value'])
                ->blade('<x-form.input name="username"/>')
                ->assertDontSee('value="old.value"', false);
    }
    
}
