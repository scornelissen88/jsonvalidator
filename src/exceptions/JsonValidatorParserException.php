<?php

namespace JsonValidator\Exceptions;

class JsonValidatorParserException extends JsonValidatorException
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}