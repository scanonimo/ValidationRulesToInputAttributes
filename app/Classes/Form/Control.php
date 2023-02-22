<?php

namespace App\Classes\Form;

use Illuminate\Support\Collection;

/**
 * @author Ariel Del Valle Lozano <arielenterdev@gmail.com>
 * @copyright Copyright (c) 2023, Ariel Del Valle Lozano
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License (GPL) version 3
 */
class Control extends Collection {

    protected array $stringDataControlTypes = [
        'text',
        'password'
    ];

    public function __construct($items = []) {
        if (!empty(func_get_args())) {
            call_user_func_array(array($this, 'add'), func_get_args());
        }
    }

    public function add($item) {
        foreach (func_get_args() as $item) {
            if (is_null($item)) {
                continue;
            }
            $attributes = $this->getArrayableItems($item);

            foreach ($attributes as $name => $value) {
                $this->offsetSet($name, $value);
            }
        }
    }

    public function offsetSet($key, $value): void {
        if (is_null($key) || is_int($key)) {
            $key = $value;
        }
        $this->items[$key] = $value;
        if (in_array($key, ['type', 'max', 'min', 'maxlength', 'minlength'])) {
            $this->maxMinSupportedValues();
        }
    }

    protected function maxMinSupportedValues() {
        if (!$this->has('type')) {
            return;
        }

        if (in_array($this['type'], $this->stringDataControlTypes)) {
            $this->stringMaxMin();
        }
    }

    protected function stringMaxMin() {
        foreach (['max', 'min'] as $name) {
            if ($this->has($name)) {
                $this->items[$name . 'length'] = $this->items[$name];
                unset($this->items[$name]);
            }
        }
        foreach (['maxlength', 'minlength'] as $name) {
            if ($this->has($name)) {
                if (!is_numeric($this->items[$name])) {
                    unset($this->items[$name]);
                    continue;
                }
                $this->items[$name] = intval(ceil(
                                floatval($this->items[$name])
                ));
            }
        }
    }

}
