<?php
namespace Helmich\JsonAssert;


use Helmich\JsonAssert\Constraint\JsonValueEquals;
use PHPUnit_Framework_Constraint as Constraint;

trait JsonAssertions
{



    abstract protected function assertThat($value, Constraint $constraint, $message = '');



    public function assertJsonValueEquals($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertThat($jsonDocument, new JsonValueEquals($jsonPath, $expectedValue));
    }



    public function assertAllJsonValuesEqual($jsonDocument, $jsonPath, $expectedValue)
    {
        $this->assertThat($jsonDocument, new JsonValueEquals($jsonPath, $expectedValue, TRUE));
    }

}