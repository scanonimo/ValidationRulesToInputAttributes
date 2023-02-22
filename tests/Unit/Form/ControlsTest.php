<?php

namespace Tests\Unit\Form;

use App\Classes\Form\Control;
use App\Classes\Form\Controls;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ControlsTest extends TestCase {

    /** @test */
    function is_instance_of_collection() {
        $this->assertInstanceOf(Collection::class, (new Control()));
    }

    /** @test */
    function can_handdle_multiple_arguments() {
        $controls1 = new Controls(
                ['name' => ['type' => 'text', 'name' => 'name', 'max' => 60]],
                [
            'age' => ['type' => 'number', 'name' => 'age', 'required'],
            'email' => ['type' => 'email', 'name' => 'email']
                ],
                [['type' => 'password', 'name' => 'password']]
        );
        $this->assertEquals(4, $controls1->count());
        
        $controls2 = new Controls();
        $controls2->add(
                ['name' => ['type' => 'text', 'name' => 'name', 'max' => 60]],
                [
            'age' => ['type' => 'number', 'name' => 'age', 'required'],
            'email' => ['type' => 'email', 'name' => 'email']
                ],
                [['type' => 'password', 'name' => 'password']]
        );
        $this->assertEquals(4, $controls2->count());
    }
    
    /** @test */
    function all_items_in_the_collection_are_instance_of_control() {
        $controls = new Controls(
                [
            'age' => ['type' => 'number', 'name' => 'age', 'required'],
            'email' => ['type' => 'email', 'name' => 'email']
                ],
        );
        foreach ($controls->all() as $control) {
            $this->assertInstanceOf(Control::class, $control);
        }
    }
    
    /** @test */
    function if_the_control_to_be_added_has_a_numeric_key_and_it_is_empty_it_wont_be_added_at_all() {
        $controls = new Controls([[]]);
        $controls->add(['1' => []]);
        $this->assertEquals(0, $controls->count());
    }
    
    /** @test */
    function adding_an_empty_control_with_a_non_numeric_key_is_acceptable() {
        $controls = new Controls(['name' => []]);
        $controls->add('email');
        $this->assertEquals(2, $controls->count());
    }
    
    /** @test */
    function adding_a_none_empty_control_with_a_numeric_key_is_acceptable() {
        $controls = new Controls([['type' => 'email', 'name' => 'email']]);
        $controls->add(['1' => ['type' => 'password']]);
        $this->assertEquals(2, $controls->count());
    }
    
    /** @test */
    function can_add_attributes_to_its_items() {
        $controls = new Controls('name', 'email');
        $controls->addAttributes('required');
        foreach ($controls->all() as $control) {
            $this->assertArrayHasKey('required', $control->toArray());
        }
    }
    
    /** @test */
    function the_add_method_returns_only_the_newly_added_items() {
        $controls = new Controls('name');
        $controls->add('email')->addAttributes('required');
        $this->assertArrayNotHasKey('required', $controls['name']->toArray());
        $this->assertArrayHasKey('required', $controls['email']->toArray());
    }

    /** @test */
    function name_attribute_can_be_automatically_added_if_the_control_has_a_non_numeric_key() {
        $controls = new Controls(
                'password', 
                ['age' => ['type' => 'number', 'required']], 
                [['type' => 'email', 'max' => 60]], 
                ['1' => ['type' => 'text', 'min' => 3]]
            );
        $controls->addNameAttr();
        $this->assertArrayHasKey('name', $controls['password']->toArray());
        $this->assertEquals('password', $controls['password']->toArray()['name']);
        $this->assertArrayHasKey('name', $controls['age']->toArray());
        $this->assertEquals('age', $controls['age']->toArray()['name']);
        $this->assertArrayNotHasKey('name', $controls[0]->toArray());
        $this->assertArrayNotHasKey('name', $controls['1']->toArray());
    }
}
