<?php
namespace Helmich\JsonAssert;


use Helmich\JsonAssert\Constraint\JsonValueMatches;
use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use PHPUnit_Framework_Constraint as Constraint;


trait JsonAssertions
{



    abstract protected function assertThat($value, Constraint $constraint, $message = '');



    public function assertJsonValueMatches($jsonDocument, $jsonPath, Constraint $constraint)
    {
        $this->assertThat($jsonDocument, new JsonValueMatches($jsonPath, $constraint));
    }



    public function assertAllJsonValuesMatch($jsonDocument, $jsonPath, Constraint $constraint)
    {
        $this->assertThat($jsonDocument, new JsonValueMatches($jsonPath, $constraint, TRUE));
    }



    public function assertJsonValueEquals($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertJsonValueMatches(
            $jsonDocument,
            $jsonPath,
            new \PHPUnit_Framework_Constraint_IsEqual($expectedValue)
        );
    }



    public function assertAllJsonValuesEqual($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertAllJsonValuesMatch(
            $jsonDocument,
            $jsonPath,
            new \PHPUnit_Framework_Constraint_IsEqual($expectedValue)
        );
    }



    public function assertJsonDocumentMatches($jsonDocument, array $constraints)
    {
        $this->assertThat($jsonDocument, new JsonValueMatchesMany($constraints));
    }

}