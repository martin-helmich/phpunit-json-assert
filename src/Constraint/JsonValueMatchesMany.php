<?php
namespace Helmich\JsonAssert\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;

/**
 * Constraint that asserts that a JSON document matches an entire set of JSON
 * value constraints.
 *
 * @package    Helmich\JsonAssert
 * @subpackage Constraint
 */
class JsonValueMatchesMany extends Constraint
{

    /** @var JsonValueMatches[] */
    private $constraints = array();

    /**
     * JsonValueMatchesMany constructor.
     *
     * @param array $constraints A set of constraints. This is a key-value map
     *                           where each key is a JSON path expression,
     *                           associated with a constraint that all values
     *                           matched by that expression must fulfill.
     */
    public function __construct(array $constraints)
    {
        foreach ($constraints as $key => $constraint) {
            if (!$constraint instanceof Constraint) {
                $constraint = new IsEqual($constraint);
            }

            $this->constraints[] = new JsonValueMatches($key, $constraint);
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return implode(
            ' and ',
            array_map(
                function (Constraint $constraint) {
                    return $constraint->toString();
                },
                $this->constraints
            )
        );
    }

    /**
     * @inheritdoc
     */
    protected function matches($other): bool
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->evaluate($other, '', true)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns a string representation of matches that evaluate to false.
     *
     * @return string
     */
    protected function additionalFailureDescription($other): string
    {
        /** @var string[] */
        $failedConstraints = array();

        foreach ($this->constraints as $constraint) {
            if (!$constraint->evaluate($other, '', true)) {
                $failedConstraints[] = $constraint->toString();
            }
        }
        
        return "\n" . implode("\n", $failedConstraints);
    }
}
