<?php

namespace JsonValidator\Validator\Parsers;

/**
 * Class BoolParser
 * @package JsonValidator\Parsers
 */
class BoolParser extends Parser
{
    /**
     * @param $value
     * @param array $args
     * @return bool
     */
    public function validate($value, array $args): bool
    {
        return is_bool($value) || $value === 0 || $value === 1;
    }
}