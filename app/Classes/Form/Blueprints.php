<?php

namespace App\Classes\Form;

/**
 * @author Ariel Del Valle Lozano <arielenterdev@gmail.com>
 * @copyright Copyright (c) 2023, Ariel Del Valle Lozano
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License (GPL) version 3
 */
class Blueprints {
    public string $errorBag = 'default';
    public Controls $controls;
    
    public function __construct(
    ) {
        $this->controls = new Controls();
        
    }
}
