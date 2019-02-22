<?php

namespace JsonValidator\Validator\Parsers;

/**
 * Class EnumParser
 * @package JsonValidator\Parsers
 */
class EnumParser extends Parser
{
    /**
     * @param $value
     * @param array $args
     * @return bool
     */
    public function validate($value, array $args): bool
    {
        return in_array($value, $args, true);
    }
}