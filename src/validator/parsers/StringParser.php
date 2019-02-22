<?php

namespace JsonValidator\Validator\Parsers;

/**
 * Class StringParser
 * @package JsonValidator\Parsers
 */
class StringParser extends Parser
{
    /**
     * @param $value
     * @param array $args
     * @return bool
     */
    public function validate($value, array $args): bool
    {
        return is_string($value);
    }
}