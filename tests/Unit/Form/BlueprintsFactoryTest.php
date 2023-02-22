<?php

namespace Tests\Unit\Form;

use App\Classes\Form\Blueprints;
use Facades\App\Classes\Form\BlueprintsFactory;
use App\Classes\Form\Controls;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class BlueprintsFactoryTest extends TestCase {

    /** @test */
    function returnes_an_instance_of_blueprints() {
        $this->assertInstanceOf(
                Blueprints::class,
                BlueprintsFactory::make()
        );
    }

    /** @test */
    function creates_controls_out_of_validation_rules() {
        $createdControls = BlueprintsFactory::make($this->exampleRules())
                ->controls;
        $expectedControls = new Controls($this->expectedControls());
        $this->assertEquals($expectedControls, $createdControls);
    }

    /** @test */
    function validation_rules_can_be_source_out_from_a_form_request() {
        $formRequest = new class extends FormRequest {

            public function rules() {
                return [
                    'username' => ['required', 'string']
                ];
            }
        };
        $createdControls = BlueprintsFactory::make($formRequest)->controls;
        $expectedControls = new Controls([
            'username' => ['name' => 'username', 'required' => 'required',
                'type' => 'text']
        ]);

        $this->assertEquals($expectedControls, $createdControls);
    }
    
    /** @test */
    function errorBag_can_be_given() {
        $errorBag = BlueprintsFactory::make(errorBag: 'exampleBag')->errorBag;
        $this->assertEquals('exampleBag', $errorBag);
    }
    
    /** @test */
    function if_no_errorBag_argument_is_given_default_is_used() {
        $errorBag = BlueprintsFactory::make()->errorBag;
        $this->assertEquals('default', $errorBag);
    }
    
    /** @test */
    function errorBag_can_be_pulled_out_from_a_form_request() {
        $formRequest = new class extends FormRequest {

            protected $errorBag = 'exampleBag';
        };
        $errorBag = BlueprintsFactory::make($formRequest)->errorBag;
        $this->assertEquals('exampleBag', $errorBag);
    }
    
    /** @test */
    function if_both_a_form_request_and_an_errorBag_are_given_the_form_request_errorBag_is_prioritized() {
        $formRequest = new class extends FormRequest {

            protected $errorBag = 'prioritizedBag';
        };
        $errorBag = BlueprintsFactory::make($formRequest, 'ignoredBag')->errorBag;
        $this->assertEquals('prioritizedBag', $errorBag);
    }
    
    /** @test */
    function if_input_type_cant_be_infered_from_the_validation_rules_text_type_is_stablished() {
        $input = BlueprintsFactory::make(['username' => ['min:3', 'max:64']])
                ->controls['username']->toArray();
        $this->assertArrayHasKey('type', $input);
        $this->assertEquals('text', $input['type']);
    }

    public function exampleRules() {
        return [
            'username' => ['required', 'string', 'min:3', 'max:64',
                'regex:/^[a-z]([a-z0-9]|[a-z0-9]\.[a-z0-9])*$/i'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)]
        ];
    }

    public function expectedControls() {
        return [
            'username' => ['name' => 'username', 'required' => 'required',
                'type' => 'text', 'minlength' => 3, 'maxlength' => 64,
                'pattern' => '^[a-z]([a-z0-9]|[a-z0-9]\.[a-z0-9])*$'],
            'password' => ['name' => 'password', 'required' => 'required',
                'type' => 'password', 'minlength' => 6],
            'password_confirmation' => ['name' => 'password_confirmation',
                'type' => 'password', 'required' => 'required']
        ];
    }

}
