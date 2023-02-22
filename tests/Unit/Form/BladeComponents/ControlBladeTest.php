<?php

namespace Tests\Unit\Form\BladeComponents;

use App\Classes\Form\Control;
use Illuminate\View\ViewException;
use Tests\TestCase;
use function __;

class ControlBladeTest extends TestCase {

    /** @test */
    function renders_a_form_control_with_labels() {
        $attributes = 'name="username" type="text"';
        $this->blade("<x-form.control $attributes />")
                ->assertSeeInOrder(['<label', '>',
                    __('validation.attributes.username'), '</label>', '<input', 
                    $attributes, '>'], false);
    }
    
    /** @test */
    function at_lest_a_name_an_id_or_a_key_attribute_must_be_given() {
        $this->assertThrows(
                fn () => $this->blade('<x-form.control type="text"/>'),
                ViewException::class
        );
    }
    
    /** @test */
    function if_attribute_id_is_not_given_one_will_be_made_base_on_name_or_key_and_errorBag_attributes() {
        $this->blade('<x-form.control name="username" />')
                ->assertSeeInOrder(['<label', 'for="username"', '>',
                    __('validation.attributes.username'), '</label>', '<input', 
                    'id="username"', '>'], false);
        
        $this->blade('<x-form.control key="username" />')
                ->assertSeeInOrder(['<label', 'for="username"', '>',
                    __('validation.attributes.username'), '</label>', '<input', 
                    'id="username"', '>'], false);
        
        $this->blade('<x-form.control name="username" errorBag="bag1"/>')
                ->assertSeeInOrder(['<label', 'for="username_bag1"', '>',
                    __('validation.attributes.username'), '</label>', '<input', 
                    'id="username_bag1"', '>'], false);
        
    }
    
    /** @test */
    function second_label_shows_errors_found() {
        $attributes = 'name="username" type="text"';
        $errorToShow = 'Error for username.';
        $this->withViewErrors(["username" => $errorToShow], "sameBag")
                ->blade("<x-form.control $attributes errorBag='sameBag'/>")
                ->assertSeeInOrder(['<label', '>',
                    __('validation.attributes.username'), '</label>',
                    '<input', $attributes, '>', '<label', 
                    '>', $errorToShow, '</label>'], false);
    }
    
    /** @test */
    function error_bags_must_match_to_show_the_error() {
        $errorToShow = 'Error for username.';
        $this->withViewErrors(["username" => $errorToShow], 'notTheSame')
                ->blade('<x-form.control name="username" errorBag="diferent"/>')
                ->assertDontSee($errorToShow, false);
    }

    /** @test */
    function if_no_errorBag_is_given_default_is_used() {
        $errorToShow = 'Error for username.';
        $this->withViewErrors(["username" => $errorToShow])
                ->blade('<x-form.control name="username"/>')
                ->assertSee($errorToShow, false);        
    }
    
}
