<?php

namespace JsonValidator\Exceptions;

use JsonValidator\Validators\Validator;

class JsonValidatorErrorException extends JsonValidatorException
{
    public function __construct($message, Validator $validator = null)
    {
        if ($validator !== null) {
            $message = str_replace('{property}', implode('->', $validator->getDepth()), $message);
        }

        parent::__construct($message);
    }
}