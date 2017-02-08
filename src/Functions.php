<?php

use Helmich\JsonAssert\Constraint\JsonValueMatches;
use Helmich\JsonAssert\Constraint\JsonValueMatchesMany;
use Helmich\JsonAssert\Constraint\JsonValueMatchesSchema;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

function containsJsonValue(string $path, $constraint): JsonValueMatches
{
    if (!$constraint instanceof Constraint) {
        $constraint = new IsEqual($constraint);
    }

    return new JsonValueMatches($path, $constraint);
}

function matchesJsonConstraints(array $constraints): JsonValueMatchesMany
{
    return new JsonValueMatchesMany($constraints);
}

function matchesJsonSchema(array $schema): JsonValueMatchesSchema
{
    return new JsonValueMatchesSchema($schema);
}
