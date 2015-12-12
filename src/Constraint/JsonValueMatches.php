<?php
namespace Helmich\JsonAssert\Constraint;

use Flow\JSONPath\JSONPath;
use PHPUnit_Framework_Constraint as Constraint;


class JsonValueMatches extends Constraint
{



    /** @var string */
    private $jsonPath;


    /** @var Constraint */
    private $constraint;


    /** @var bool */
    private $matchAll;



    public function __construct($jsonPath, Constraint $constraint, $matchAll = FALSE)
    {
        parent::__construct();

        $this->jsonPath   = $jsonPath;
        $this->constraint = $constraint;
        $this->matchAll   = $matchAll;
    }



    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "matches " . $this->constraint->toString() . " at JSON path '{$this->jsonPath}'";
    }



    protected function matches($other)
    {
        if (is_string($other))
        {
            $other = json_decode($other);
        }

        $result = (new JSONPath($other))->find($this->jsonPath);
        if (!isset($result[0]))
        {
            return FALSE;
        }

        if ($this->matchAll)
        {
            $combineFunc = function ($a, $b)
            {
                return ($a === NULL) ? $b : $a && $b;
            };
        }
        else
        {
            $combineFunc = function ($a, $b)
            {
                return ($a === NULL) ? $b : $a || $b;
            };
        }

        $matches = NULL;

        foreach ($result as $k => $v)
        {
            if ($v instanceof JSONPath)
            {
                $v = $v->data();
            }

            $singleMatchResult = $this->constraint->evaluate($v , '', TRUE);
            $matches           = $combineFunc($matches, $singleMatchResult);
        }

        return $matches;
    }
}