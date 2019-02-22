<?php

namespace JsonValidator\Validator\Parsers;

/**
 * Class Parser
 * @package JsonValidator\Parsers
 */
abstract class Parser
{
    /**
     * @param $value
     * @param array $args
     * @return bool
     */
    abstract public function validate($value, array $args): bool;

    /**
     * @param $value
     */
    public function convert(&$value)
    {
        // Placeholder method
    }
}