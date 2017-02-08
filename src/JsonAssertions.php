<?php
namespace Helmich\JsonAssert;

use Helmich\JsonAssert\Constraint\JsonValueMatches;
use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\JsonAssert\Constraint\JsonValueMatchesSchema;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

/**
 * A trait that can be used in test classes for easy use of JSON assertions
 *
 * @package Helmich\JsonAssert
 */
trait JsonAssertions
{

    /**
     * Asserts that at least one of a set of JSON values in a document match a
     * given constraint
     *
     * @param mixed      $jsonDocument A JSON document. If this is a string, it
     *                                 will be assumed to be an encoded JSON
     *                                 document
     * @param string     $jsonPath     The JSON path expression that should be
     *                                 matched
     * @param Constraint $constraint   The constraint that the matched value
     *                                 must match
     * @return void
     */
    public static function assertJsonValueMatches($jsonDocument, string $jsonPath, Constraint $constraint)
    {
        Assert::assertThat($jsonDocument, new JsonValueMatches($jsonPath, $constraint));
    }

    /**
     * Asserts that all of a set of JSON values in a document match a given
     * constraint
     *
     * @param mixed      $jsonDocument A JSON document. If this is a string, it
     *                                 will be assumed to be an encoded JSON
     *                                 document
     * @param string     $jsonPath     The JSON path expression that should be
     *                                 matched
     * @param Constraint $constraint   The constraint that the matched value
     *                                 must match
     * @return void
     */
    public static function assertAllJsonValuesMatch($jsonDocument, string $jsonPath, Constraint $constraint)
    {
        Assert::assertThat($jsonDocument, new JsonValueMatches($jsonPath, $constraint, true));
    }

    /**
     * Asserts that at least one of a set of JSON values in a document is equal
     * to a given value
     *
     * @param mixed  $jsonDocument  A JSON document. If this is a string, it
     *                              will be assumed to be an encoded JSON document
     * @param string $jsonPath      The JSON path expression that should be
     *                              matched
     * @param mixed  $expectedValue The value that the matched values should be
     *                              equal to.
     * @return void
     */
    public static function assertJsonValueEquals($jsonDocument, string $jsonPath, $expectedValue)
    {
        static::assertJsonValueMatches(
            $jsonDocument,
            $jsonPath,
            new IsEqual($expectedValue)
        );
    }

    /**
     * Asserts that all of a set of JSON values in a document are equal to a
     * given value
     *
     * @param mixed  $jsonDocument  A JSON document. If this is a string, it
     *                              will be assumed to be an encoded JSON
     *                              document
     * @param string $jsonPath      The JSON path expression that should be
     *                              matched
     * @param mixed  $expectedValue The value that the matched values should be
     *                              equal to.
     * @return void
     */
    public static function assertAllJsonValuesEqual($jsonDocument, string $jsonPath, $expectedValue)
    {
        static::assertAllJsonValuesMatch(
            $jsonDocument,
            $jsonPath,
            new IsEqual($expectedValue)
        );
    }

    /**
     * Asserts that a JSON document matches an entire set of constraints.
     *
     * @param mixed $jsonDocument A JSON document. If this is a string, it will
     *                            be assumed to be an encoded JSON document
     * @param array $constraints  A set of constraints. This is a key-value map
     *                            where each key is a JSON path expression,
     *                            associated with a constraint that all values
     *                            matched by that expression must fulfill.
     * @return void
     */
    public static function assertJsonDocumentMatches($jsonDocument, array $constraints)
    {
        Assert::assertThat($jsonDocument, new JsonValueMatchesMany($constraints));
    }

    /**
     * Assert that a JSON document matches a given JSON schema.
     *
     * @param mixed $jsonDocument A JSON document. If this is a string, it will
     *                            be assumed to be an encoded JSON document
     * @param array $schema       A JSON schema
     * @return void
     */
    public static function assertJsonDocumentMatchesSchema($jsonDocument, $schema)
    {
        Assert::assertThat($jsonDocument, new JsonValueMatchesSchema($schema));
    }
}
