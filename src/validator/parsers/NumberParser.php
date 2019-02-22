<?php

namespace JsonValidator\Validator\Parsers;

/**
 * Class NumberParser
 * @package JsonValidator\Parsers
 */
class NumberParser extends Parser
{
    public function validate($value, array $args): bool
    {
        return is_string($value) && ctype_digit($value);
    }

    /**
     * @param $value
     */
    public function convert(&$value)
    {
        $value = (int) $value;
    }
}