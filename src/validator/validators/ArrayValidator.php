<?php

namespace JsonValidator\Validator\Validators;

use JsonValidator\Exceptions\JsonValidatorErrorException;

/**
 * Class ArrayValidator validates if the value is an array, if so check recursively if required
 *
 * @package JsonValidator\Validators
 */
class ArrayValidator extends Validator
{
    /**
     * @param $rules
     * @param $value
     * @throws JsonValidatorErrorException
     */
    public function validate($rules, &$value): void
    {
        if (!is_array($value)) {
            throw new JsonValidatorErrorException('Property {property} is required to be an array', $this->_validator);
        }

        // If array contains a single unnamed object, validate each array iteration against that object
        if (isset($rules[0]) && is_object($rules[0])) {
            foreach ($value as $index => $item) {
                $this->_validator->increaseDepth($index);

                $this->_validator->validate($rules[0], $item);

                $this->_validator->decreaseDepth();
            }
        }

        if (!empty($rule)) {
            foreach ($rule as $r => $item) {
                if (is_string($r)) {
                    $this->_validator->validate($r, $item);
                }
            }
        }

        // @todo: add more options for in-array validation
    }
}