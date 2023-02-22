<?php

namespace App\Classes\Form;

/**
 * @author Ariel Del Valle Lozano <arielenterdev@gmail.com>
 * @copyright Copyright (c) 2023, Ariel Del Valle Lozano
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License (GPL) version 3
 */
class RuleToAttribute {
    public function string() {
        return ['type' => 'text'];
    }
    
    public function numeric() {
        return ['type' => 'number'];
    }
    
    public function password() {
        return ['type' => 'password'];
    }
    
    public function min($attributes) {
        return ['min' => $attributes[0]];
    }
    
    public function max($attributes) {
        return ['max' => $attributes[0]];
    }
    
    public function required() {
        return ['required' => 'required'];
    }
    
    public function regex($attributes) {
        return [
            'pattern' => preg_replace('/(^[\/])|([\/].*$)/','',$attributes[0])
        ];
    }
}
