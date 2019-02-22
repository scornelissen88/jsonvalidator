<?php

namespace JsonValidator\Validator;

use JsonValidator\Exceptions\JsonValidatorErrorException;
use JsonValidator\Parsers\Parser;
use JsonValidator\Validator\Validators\ArrayValidator;
use JsonValidator\Validator\Validators\ObjectValidator;
use JsonValidator\Validator\Validators\ScalarValidator;

/**
 * Class Validator validates JSON data based on the given schema. Both should already be converted to objects
 *
 * @package JsonValidator\Validators
 */
class Validator
{
    /**
     * Recursion depth
     *
     * @var array
     */
    private $_depth = [];


    private $_arrayValidator;
    private $_scalarValidator;
    private $_objectValidator;

    /**
     * Validator constructor
     *
     * @param Parser[] $parsers
     * @param $rule_delimiter
     * @param $arg_delimiter
     */
    public function __construct(array $parsers, $rule_delimiter, $arg_delimiter)
    {
        $this->_scalarValidator = new ScalarValidator($this, $parsers, $rule_delimiter, $arg_delimiter);
        $this->_arrayValidator = new ArrayValidator($this);
        $this->_objectValidator = new ObjectValidator($this);
    }

    /**
     * @param object|array $schema
     * @param object|array $data
     * @throws JsonValidatorErrorException
     */
    public function validate($schema, &$data)
    {
        foreach ($schema as $property => $rules)
        {
            $this->increaseDepth($property);

            if (!property_exists($data, $property)) {
                throw new JsonValidatorErrorException('Property {property} not found in data', $this);
            }

            switch (gettype($rules)) {
                case 'object':
                    $this->_objectValidator->validate($rules, $data->$property);

                    break;
                case 'array':
                    $this->_arrayValidator->validate($rules, $data->$property);

                    break;
                case 'string':
                    $this->_scalarValidator->validate($rules, $data->$property);

                    break;
                default:
                    throw new JsonValidatorErrorException('Property {property} has no valid validation rule', $this);
            }

            $this->decreaseDepth();
        }
    }

    /**
     * Increases depth with one layer, named $property
     *
     * @param string $property
     */
    public function increaseDepth(string $property): void
    {
        $this->_depth[] = $property;
    }

    /**
     * Decreases depth with one layer
     */
    public function decreaseDepth(): void
    {
        array_pop($this->_depth);
    }

    /**
     * @return array
     */
    public function getDepth(): array
    {
        return $this->_depth;
    }
}