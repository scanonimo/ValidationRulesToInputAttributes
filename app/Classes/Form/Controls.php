<?php

namespace App\Classes\Form;

use Illuminate\Support\Collection;

/**
 * @author Ariel Del Valle Lozano <arielenterdev@gmail.com>
 * @copyright Copyright (c) 2023, Ariel Del Valle Lozano
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License (GPL) version 3
 */
class Controls extends Collection {

    public function __construct($items = []) {
        if (empty(func_get_args())) {
            return $this;
        }
        return call_user_func_array(array($this, 'add'), func_get_args());
    }

    public function add($item) {
        $addedKeys = array();

        foreach (func_get_args() as $item) {
            $controls = $this->getArrayableItems($item);

            foreach ($controls as $name => $control) {
                if (is_int($name)) {
                    if (empty($control)) {
                        continue;
                    }
                    if (is_string($control)) {
                        $name = $control;
                        $control = [];
                    }
                }
                $this->offsetSet($name, $control);
                $addedKeys[] = array_key_last($this->items);
            }
        }
        return $this->only($addedKeys);
    }

    public function only($keys) {
        if (is_null($keys = $this->keysInterpreter(func_get_args()))) {
            return $this;
        }

        if ($keys == array_keys($this->items)) {
            return $this;
        }

        $newObj = (new Controls);
        foreach ($keys as $key) {
            if (isset($this->items[$key])) {
                $newObj->items[$key] = $this->items[$key];
            }
        }

        return $newObj;
    }

    private function keysInterpreter($keys) {
        $firstValue = $keys[0];

        if (is_null($firstValue)) {
            return $firstValue;
        }

        if ($firstValue instanceof Enumerable) {
            return $firstValue->all();
        }

        if (is_array($firstValue)) {
            return $firstValue;
        }

        return $keys;
    }

    public function except($keys) {
        if (is_null($keys = $this->keysInterpreter(func_get_args()))) {
            return $this;
        }

        $newObj = new Controls;

        $newObj->items = $this->items;

        foreach ($keys as $key) {
            unset($newObj->items[$key]);
        }

        return $newObj;
    }

    public function offsetSet($key, $value): void {
        $attributes = $this->getArrayableItems($value);
        $control = new Control($attributes);
        if (is_null($key)) {
            $this->items[] = $control;
        } else {
            $this->items[$key] = $control;
        }
    }

    public function addAttributes(...$items) {
        if (empty($items)) {
            return $this;
        }
        $attributes = array();
        foreach ($items as $item) {
            if (empty($item)) {
                continue;
            }
            $attributes[] = $this->getArrayableItems($item);
        }
        foreach ($attributes as $attribute) {
            foreach ($this->items as $control) {
                foreach ($attribute as $key => $value) {
                    $control->offsetSet($key, $value);
                }
            }
        }
        return $this;
    }

    public function addNameAttr() {
        foreach ($this->items as $name => $control) {
            if (!is_numeric($name)) {
                $control['name'] = $name;
            }
        }
        return $this;
    }

}
