<?php

namespace Tests\Unit\Form;

use App\Classes\Form\Control;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ControlTest extends TestCase {

    protected array $stringDataControlTypes = [
        'text',
        'password'
    ];

    /** @test */
    function is_instance_of_collection() {
        $this->assertInstanceOf(Collection::class, (new Control()));
    }

    /** @test */
    function can_handdle_multiple_arguments() {
        $control1 = new Control(
                ['type' => 'text'],
                ['name' => 'field1', 'minlength' => '3'],
                ['maxlength' => '60']
        );
        $this->assertEquals(4, $control1->count());

        $control2 = new Control();
        $control2->add(
                ['type' => 'text'],
                ['name' => 'field1', 'minlength' => '3'],
                ['maxlength' => '60']
        );
        $this->assertEquals(4, $control1->count());
    }

    /** @test */
    function if_key_is_not_given_value_will_also_be_the_key() {
        $control = new Control();
        $control[] = 'disabled';
        $control->add('readonly');
        $this->assertEquals(
                ['disabled' => 'disabled', 'readonly' => 'readonly'],
                $control->toArray()
        );
    }

    /** @test */
    function if_key_is_numeric_value_will_also_be_the_key() {
        $control = new Control();
        $control[0] = 'disabled';
        $control->add(['1' => 'readonly']);
        $this->assertEquals(
                ['disabled' => 'disabled', 'readonly' => 'readonly'],
                $control->toArray()
        );
    }

    /** @test */
    function if_control_type_given_handdles_string_values_max_and_min_attributes_should_be_change_to_maxlength_and_minlength() {
        foreach ($this->stringDataControlTypes as $type) {
            $control = new Control(['max' => '60']);
            $control['type'] = $type;
            $control['min'] = 3;
            $this->assertEquals(
                    ['maxlength' => 60, 'type' => $type, 'minlength' => 3],
                    $control->toArray()
            );
        }
    }

    /** @test */
    function if_control_type_given_handdles_string_values_non_numeric_max_min_attributes_would_be_ignored() {
        foreach ($this->stringDataControlTypes as $type) {
            $control = new Control(['max' => 'non_numeric_value']);
            $control['type'] = $type;
            $control['min'] = '3';
            $this->assertEquals(
                    ['type' => $type, 'minlength' => 3],
                    $control->toArray()
            );
        }
    }

    /** @test */
    function if_control_type_given_handdles_string_values_non_integer_numeric_values_would_be_ceil() {
        foreach ($this->stringDataControlTypes as $type) {
            $control = new Control(['max' => 60.1]);
            $control['type'] = $type;
            $control['min'] = '3.9';
            $this->assertEquals(
                    ['maxlength' => 61, 'type' => $type, 'minlength' => 4],
                    $control->toArray()
            );
        }
    }

}
