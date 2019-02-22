<?php

namespace JsonValidator\Validator\Parsers;

/**
 * Class NullParser
 * @package JsonValidator\Parsers
 */
class NullParser extends Parser
{
    /**
     * @param $value
     * @param array $args
     * @return bool
     */
    public function validate($value, array $args): bool
    {
        return $value === null;
    }
}