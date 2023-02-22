<?php

namespace App\Classes\Form;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationRuleParser;
use ReflectionClass;

/**
 * @author Ariel Del Valle Lozano <arielenterdev@gmail.com>
 * @copyright Copyright (c) 2023, Ariel Del Valle Lozano
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License (GPL) version 3
 */
class BlueprintsFactory {

    protected ValidationRuleParser $rulesParser;
    protected RuleToAttribute $ruleToAttribute;
    protected Blueprints $blueprints;
    protected array $validationRules;
    protected array $stringDataControlTypes = [
        'text',
        'password',
        'captcha'
    ];

    public function __construct() {
        $this->rulesParser = (new ValidationRuleParser([]));

        $this->ruleToAttribute = new RuleToAttribute;

        $this->blueprints = new Blueprints;
    }

    public function make(
            array|FormRequest $validationRules = [],
            string $errorBag = "default"
    ): Blueprints {
        $this->setValidationRulesAndErrorBag($validationRules, $errorBag);

        $this->useTheValidationRulesToSetTheBlueprintsControls();

        return $this->blueprints;
    }

    protected function setValidationRulesAndErrorBag(
            array|FormRequest $validationRules,
            string $errorBag = "default"
    ): void {
        if (is_array($validationRules)) {
            $this->blueprints->errorBag = $errorBag;
            $array = $validationRules;
        } else {
            $this->blueprints->errorBag = (new ReflectionClass($validationRules))
                    ->getProperty('errorBag')
                    ->getValue($validationRules);
            $array = [];
            if(method_exists($validationRules, 'rules')){
                $array = $validationRules->rules();
            }
        }

        $this->validationRules = $this->rulesParser->explode($array)->rules;
    }

    protected function useTheValidationRulesToSetTheBlueprintsControls(): void {
        foreach ($this->validationRules as $key => $rules) {
            $control = $this->blueprints->controls->add($key)
                    ->addNameAttr()->first();
            $confirmationControl = null;
            foreach ($rules as $rule) {
                [$rule, $parameters] = $this->rulesParser->parse($rule);
                $control->add(
                        $this->translateRuleToHtml5Attributes($rule, $parameters)
                );
                if ($rule === 'Confirmed') {
                    $confirmationControl = $this->blueprints->controls
                                    ->add($key . '_confirmation')
                                    ->addNameAttr()->first();
                }
            }

            $this->addAdditionalAttributesConditionally(
                    $control,
                    $confirmationControl
            );
        }
    }

    protected function addAdditionalAttributesConditionally(
            Control $control,
            ?Control $confirmationControl
    ): void {
        $control['type'] ??= 'text';

        if ($confirmationControl) {
            $confirmationControl->add(['type' => $control['type']]);
            if (isset($control['required'])) {
                $confirmationControl->add('required');
            }
        }
    }

    protected function translateRuleToHtml5Attributes(
            string|object $rule,
            $parameters
    ) {
        if (is_string($rule)) {
            if (method_exists($this->ruleToAttribute, $rule)) {
                return $this->ruleToAttribute->$rule($parameters);
            }
        } else {
            $rules = $this->explodeRule($rule);
            $attributes = array();

            foreach ($rules as $rule => $parameters) {
                $parameters = [$parameters];
                if (
                        !is_null($attribute = $this
                                ->translateRuleToHtml5Attributes($rule, $parameters)
                        )
                ) {
                    $attributes = array_merge($attributes, $attribute);
                }
            }

            return $attributes;
        }
    }

    protected function explodeRule(object $rule) {
        $reflection = new ReflectionClass($rule);
        $rules[$reflection->getShortName()] = [];
        foreach ($reflection->getProperties() as $property) {
            $name = $property->name;
            $rules[$name] = $reflection->getProperty($name)->getValue($rule);
        }

        return $rules;
    }

}
