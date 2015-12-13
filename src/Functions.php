<?php

function containsJsonValue($path, $constraint)
{
    if (!$constraint instanceof PHPUnit_Framework_Constraint)
    {
        $constraint = new PHPUnit_Framework_Constraint_IsEqual($constraint);
    }

    return new \Helmich\JsonAssert\Constraint\JsonValueMatches($path, $constraint);
}

function matchesJsonConstraints(array $constraints)
{
    return new \Helmich\JsonAssert\Constraint\JsonValueMatchesMany($constraints);
}