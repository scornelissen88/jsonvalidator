<?php

namespace JsonValidator\Validator\Parsers;

/**
 * Class NumericParser
 * @package JsonValidator\Parsers
 */
class NumericParser extends Parser
{
    /**
     * @param $value
     * @param array $args
     * @return bool
     */
    public function validate($value, array $args): bool
    {
        return is_int($value) || is_float($value);
    }
}