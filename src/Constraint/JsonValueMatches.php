<?php
namespace Helmich\JsonAssert\Constraint;

use Flow\JSONPath\JSONPath;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * A simple constraints that asserts that a single value of a JSON document
 * matches a given constraint.
 *
 * @package    Helmich\JsonAssert
 * @subpackage Constraint
 */
class JsonValueMatches extends Constraint
{

    /** @var string */
    private $jsonPath;

    /** @var Constraint */
    private $constraint;

    /** @var bool */
    private $matchAll;

    /**
     * JsonValueMatches constructor.
     *
     * @param string     $jsonPath   The JSON path that identifies the value(s)
     *                               in the JSON document that the constraint
     *                               should match
     * @param Constraint $constraint The actual constraint that the selected
     *                               value(s) must match
     * @param bool       $matchAll   This flag controls how this constraint
     *                               handles multiple values. Usually, this
     *                               constraint will match successfully, when
     *                               (at least) one found value matches the
     *                               given constraint. When this flag is set,
     *                               _all_ found values must match the
     *                               constraint.
     */
    public function __construct(string $jsonPath, Constraint $constraint, bool $matchAll = false)
    {
        $this->jsonPath   = $jsonPath;
        $this->constraint = $constraint;
        $this->matchAll   = $matchAll;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString(): string
    {
        return "matches " . $this->constraint->toString() . " at JSON path '{$this->jsonPath}'";
    }

    /**
     * @inheritdoc
     */
    protected function matches($other): bool
    {
        if (is_string($other)) {
            $other = json_decode($other, true);
        }

        $result = (new JSONPath($other))->find($this->jsonPath);
        if (!isset($result[0])) {
            return false;
        }

        $combineFunc = $this->buildCombinationFunction();
        $matches     = null;

        foreach ($result as $v) {
            if ($v instanceof JSONPath) {
                $v = $v->getData();
            }

            $singleMatchResult = $this->constraint->evaluate($v, '', true);
            $matches           = $combineFunc($matches, $singleMatchResult);
        }

        return $matches;
    }

    /**
     * @return callable
     */
    protected function buildCombinationFunction(): callable
    {
        if ($this->matchAll) {
            return function ($first, $second) {
                return ($first === null) ? $second : $first && $second;
            };
        }

        return function ($first, $second) {
            return ($first === null) ? $second : $first || $second;
        };
    }
}
