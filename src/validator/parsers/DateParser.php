<?php

namespace JsonValidator\Validator\Parsers;

/**
 * Class DateParser
 * @package JsonValidator\Parsers
 */
class DateParser extends Parser
{
    /**
     * @var \DateTime|null;
     */
    private $_dateTime = null;

    /**
     * @param $value
     * @param array $args
     * @return bool
     */
    public function validate($value, array $args): bool
    {
        $format = $args[0] ?? null;

        if (is_string($format)) {
            $dateTime = \DateTime::createFromFormat($format, $value);

            if ($dateTime instanceof \DateTime) {
                $this->_dateTime = $dateTime;

                return true;
            }
        }

        return false;
    }

    /**
     * @param $value
     */
    public function convert(&$value)
    {
        $value = $this->_dateTime;
    }
}