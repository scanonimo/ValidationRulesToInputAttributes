<?php

namespace Tests\Unit\Form\BladeComponents;

use Facades\App\Classes\Form\BlueprintsFactory;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\ViewException;
use Tests\TestCase;
use Tests\Unit\Form\BlueprintsFactoryTest;
use function __;

class BuilderBladeTest extends TestCase {

    /** @test */
    function renders_a_form_and_its_content_using_blueprints() {
        $examplesRules = (new BlueprintsFactoryTest(''))
                ->exampleRules();
        $blueprints = BlueprintsFactory::make($examplesRules, 'bag1');
        $this->blade(
                '<x-form.builder :blueprints=$blueprints>This is the slot'
                . '</x-form.builder>', 
                compact('blueprints')
                )->assertSeeInOrder($this->expectedToSee(), false);
    }
    
    /** @test */
    function blueprints_attribute_must_be_given() {
        $this->assertThrows(
                fn () => $this->blade('<x-form.builder></x-form.builder>'),
                ViewException::class
        );
    }
    
    /** @test */
    function blueprints_must_be_an_instance_of_blueprints() {
        $this->assertThrows(
                fn () => $this->blade('<x-form.builder '
                        . 'blueprints="not_an_instance_of_blueprints">'
                        . '</x-form.builder>'),
                ViewException::class
        );        
    }
    
    /** @test */
    function inputs_with_hidden_type_are_render_without_labels() {
        $blueprints = BlueprintsFactory::make();
        $blueprints->controls->add(['hiddenField' => ['type' => 'hidden', 
            'name' => 'hiddenField', 'value' => '50']]);
        $this->blade('<x-form.builder :blueprints=$blueprints>'
                . '</x-form.builder>', compact('blueprints'))
                ->assertDontSee('<label', false);
    }

    protected function expectedToSee() {
        $expectedToSee = ['<form', '>', '<input', 'name="_token"', '>'];
        $expectedControls = (new BlueprintsFactoryTest(''))
                ->expectedControls();
        foreach ($expectedControls as $key => $value) {
            $attr = (new ComponentAttributeBag($value))->toHtml();
            array_push($expectedToSee, '<label', "for=\"{$key}_bag1\"", '>',
                    __('validation.attributes.' . $key), '</label>', '<input',
                    $attr, "id=\"{$key}_bag1\"", '>');
        }
        array_push($expectedToSee, 'This is the slot', '</form>');
        return $expectedToSee;
    }

}
