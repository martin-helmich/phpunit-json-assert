<?php
namespace Helmich\JsonAssert;


use Helmich\JsonAssert\Constraint\JsonValueMatches;
use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use PHPUnit_Framework_Constraint as Constraint;


/**
 * A trait that can be used in test classes for easy use of JSON assertions
 *
 * @package Helmich\JsonAssert
 */
trait JsonAssertions
{



    abstract protected function assertThat($value, Constraint $constraint, $message = '');



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
    public function assertJsonValueMatches($jsonDocument, $jsonPath, Constraint $constraint)
    {
        $this->assertThat($jsonDocument, new JsonValueMatches($jsonPath, $constraint));
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
    public function assertAllJsonValuesMatch($jsonDocument, $jsonPath, Constraint $constraint)
    {
        $this->assertThat($jsonDocument, new JsonValueMatches($jsonPath, $constraint, TRUE));
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
    public function assertJsonValueEquals($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertJsonValueMatches(
            $jsonDocument,
            $jsonPath,
            new \PHPUnit_Framework_Constraint_IsEqual($expectedValue)
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
    public function assertAllJsonValuesEqual($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertAllJsonValuesMatch(
            $jsonDocument,
            $jsonPath,
            new \PHPUnit_Framework_Constraint_IsEqual($expectedValue)
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
    public function assertJsonDocumentMatches($jsonDocument, array $constraints)
    {
        $this->assertThat($jsonDocument, new JsonValueMatchesMany($constraints));
    }

}