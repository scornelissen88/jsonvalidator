<?php

namespace JsonValidator\Validator\Validators;

/**
 * Class Validator
 * @package JsonValidator\Validator\Validators
 */
abstract class Validator
{
    /**
     * @var Validator
     */
    protected $_validator;

    /**
     * Validator constructor
     *
     * @param \JsonValidator\Validator\Validator $validator
     */
    public function __construct(\JsonValidator\Validator\Validator $validator)
    {
        $this->_validator = $validator;
    }

    /**
     * @param $rules
     * @param $value
     * @return mixed
     */
    abstract public function validate($rules, &$value);
}