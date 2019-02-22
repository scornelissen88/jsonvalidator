<?php

namespace JsonValidator;

use JsonValidator\Exceptions\JsonValidatorDecodeException;
use JsonValidator\Exceptions\JsonValidatorErrorException;
use JsonValidator\Exceptions\JsonValidatorException;
use JsonValidator\Validator\Parsers\BoolParser;
use JsonValidator\Validator\Parsers\DateParser;
use JsonValidator\Validator\Parsers\EnumParser;
use JsonValidator\Validator\Parsers\NullParser;
use JsonValidator\Validator\Parsers\NumberParser;
use JsonValidator\Validator\Parsers\NumericParser;
use JsonValidator\Validator\Parsers\Parser;
use JsonValidator\Validator\Parsers\StringParser;
use JsonValidator\Validator\Validator;

/**
 * Class JsonValidator
 */
class JsonValidator
{
    /**
     * @var mixed
     */
    private $_schema;

    /**
     * @var mixed
     */
    private $_data;

    /**
     * @var array
     */
    private $_parsers = [
        'bool' => BoolParser::class,
        'date' => DateParser::class,
        'enum' => EnumParser::class,
        'null' => NullParser::class,
        'number' => NumberParser::class,
        'int' => NumericParser::class,
        'float' => NumericParser::class,
        'string' => StringParser::class,
    ];

    /**
     * @var string
     */
    private $_rule_delimiter = '|';

    /**
     * @var string
     */
    private $_arg_delimiter = ',';

    /**
     * JsonValidator constructor.
     * @param $schema
     * @param $data
     * @throws JsonValidatorDecodeException
     */
    public function __construct($schema, $data)
    {
        $this->_schema = $this->_decodeJson($schema, 'schema');
        $this->_data = $this->_decodeJson($data, 'data');
    }

    /**
     * @param string $rule_delimiter
     */
    public function setRuleDelimiter(string $rule_delimiter)
    {
        $this->_rule_delimiter = $rule_delimiter;
    }

    /**
     * @param string $arg_delimiter
     */
    public function setArgDelimiter(string $arg_delimiter)
    {
        $this->_arg_delimiter = $arg_delimiter;
    }

    /**
     * @return object
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param string $rule
     * @param Parser $parser
     */
    public function addParser(string $rule, Parser $parser)
    {
        $this->_parsers[$rule] = $parser;
    }

    /**
     * @param bool $throw
     * @return bool
     * @throws JsonValidatorException
     */
    public function validate(bool $throw = true): bool
    {
        if (($this->_data === null || is_scalar($this->_data)) && $this->_schema !== $this->_data) {
            throw new JsonValidatorErrorException('The data does not contain an object and does not match with the schema type');
        }

        $validator = new Validator($this->_parsers, $this->_rule_delimiter, $this->_arg_delimiter);
        $validator->validate($this->_schema, $this->_data);

        return true;
    }

    /**
     * @param $json
     * @param $input
     * @return mixed
     * @throws JsonValidatorDecodeException
     */
    private function _decodeJson($json, $input)
    {
        $jsonObject = json_decode($json);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $jsonObject;
        }

        throw new JsonValidatorDecodeException(sprintf('The %s json could not be decoded: %s', $input, json_last_error_msg()));
    }
}