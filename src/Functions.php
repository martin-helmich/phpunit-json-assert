<?php

use Helmich\JsonAssert\Constraint\JsonValueMatches;
use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\JsonAssert\Constraint\JsonValueMatchesSchema;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

function containsJsonValue($path, $constraint)
{
    if (!$constraint instanceof Constraint) {
        $constraint = new IsEqual($constraint);
    }

    return new JsonValueMatches($path, $constraint);
}

function matchesJsonConstraints(array $constraints)
{
    return new JsonValueMatchesMany($constraints);
}

function matchesJsonSchema(array $schema)
{
    return new JsonValueMatchesSchema($schema);
}
