<?php

namespace JsonValidator\Validator\Validators;

use JsonValidator\Exceptions\JsonValidatorErrorException;
use JsonValidator\Exceptions\JsonValidatorParserException;
use JsonValidator\Validator\Parsers\Parser;

/**
 * Class ArrayValidator validates if the value is an array, if so check recursively if required
 *
 * @package JsonValidator\Validators
 */
class ScalarValidator extends Validator
{
    /**
     * @var array
     */
    private $_parsers;

    /**
     * @var string
     */
    private $_rule_delimiter;

    /**
     * @var string
     */
    private $_arg_delimiter;

    /**
     * ScalarValidator constructor
     *
     * @param \JsonValidator\Validator\Validator $validator
     * @param array $parsers
     * @param string $rule_delimiter
     * @param string $arg_delimiter
     */
    public function __construct(\JsonValidator\Validator\Validator $validator, array $parsers, string $rule_delimiter, string $arg_delimiter)
    {
        parent::__construct($validator);

        $this->_parsers = $parsers;
        $this->_rule_delimiter = $rule_delimiter;
        $this->_arg_delimiter = $arg_delimiter;
    }

    /**
     * @param $rules
     * @param $value
     * @return void
     * @throws JsonValidatorErrorException
     */
    public function validate($rules, &$value)
    {
        foreach (explode($this->_rule_delimiter, $rules) as $rule) {
            $args = [];

            if (preg_match('/^(?<rule>.+)\[(?<args>.+)\]$/', $rule, $match)) {
                /**
                 * @var string $rule
                 * @var string $arg
                 */
                extract($match, EXTR_OVERWRITE);
            }

            if (is_string($args)) {
                $args = explode($this->_arg_delimiter, $args);
            }

            if ($this->_getParser($rule)->validate($value, $args)) {
                $this->_getParser($rule)->convert($value);

                return;
            }
        }

        throw new JsonValidatorErrorException(sprintf('Property {property} does not satisfy any of these rules: %s', $rules), $this->_validator);
    }

    /**
     * @param $rule
     * @return Parser|mixed
     * @throws JsonValidatorParserException
     */
    private function _getParser($rule)
    {
        if (array_key_exists($rule, $this->_parsers)) {
            $parser = &$this->_parsers[$rule];

            if (is_string($parser) && class_exists($parser)) {
                $parser = new $parser;

                if (!$parser instanceof Parser) {
                    throw new JsonValidatorParserException(sprintf('A parser class should extend %s', Parser::class));
                }
            }

            return $parser;
        }

        throw new JsonValidatorParserException(sprintf('No parser set for rule %s', $rule));
    }
}