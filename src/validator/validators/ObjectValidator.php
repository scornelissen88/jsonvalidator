<?php

namespace JsonValidator\Validator\Validators;

use JsonValidator\Exceptions\JsonValidatorErrorException;

/**
 * Class ArrayValidator validates if the value is an array, if so check recursively if required
 *
 * @package JsonValidator\Validators
 */
class ObjectValidator extends Validator
{
    /**
     * @param $rules
     * @param $value
     * @throws JsonValidatorErrorException
     */
    public function validate($rules, &$value): void
    {
        if (!is_object($value)) {
            throw new JsonValidatorErrorException('Property {property} is required to be an object', $this->_validator);
        }

        $this->_validator->validate($rules, $value);
    }
}