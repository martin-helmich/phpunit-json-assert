<?php
namespace Helmich\JsonAssert\Constraint;

use JsonSchema\Validator;
use PHPUnit\Framework\Constraint\Constraint;
use stdClass;

/**
 * A constraint for asserting that a JSON document matches a schema
 *
 * @package    Helmich\JsonAssert
 * @subpackage Constraint
 */
class JsonValueMatchesSchema extends Constraint
{
    /**
     * @var array|stdClass
     */
    private $schema;

    /**
     * JsonValueMatchesSchema constructor.
     *
     * @param array|stdClass $schema The JSON schema
     */
    public function __construct($schema)
    {
        $this->schema = $this->forceToObject($schema);
    }

    /**
     * VERY dirty hack to force a JSON document into an object.
     *
     * Yell if you can think of something better.
     *
     * @param array|stdClass $jsonDocument
     * @return stdClass
     */
    private function forceToObject($jsonDocument)
    {
        if (is_string($jsonDocument)) {
            return json_decode($jsonDocument);
        }

        return json_decode(json_encode($jsonDocument));
    }

    /**
     * @inheritdoc
     */
    protected function matches($other): bool
    {
        $other = $this->forceToObject($other);

        $validator = new Validator();
        $validator->check($other, $this->schema);

        return $validator->isValid();
    }

    /**
     * @inheritdoc
     */
    protected function additionalFailureDescription($other): string
    {
        $other = $this->forceToObject($other);

        $validator = new Validator();
        $validator->check($other, $this->schema);

        return implode("\n", array_map(function ($error) {
            return sprintf("[%s] %s", $error['property'], $error['message']);
        }, $validator->getErrors()));
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return 'matches JSON schema';
    }
}
