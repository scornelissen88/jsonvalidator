<?php

namespace JsonValidator\Exceptions;

class JsonValidatorDecodeException extends JsonValidatorException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}