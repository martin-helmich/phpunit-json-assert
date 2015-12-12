<?php
namespace Helmich\JsonAssert\Constraint;


use Flow\JSONPath\JSONPath;
use PHPUnit_Framework_Constraint as Constraint;


class JsonValueEquals extends Constraint
{



    /**
     * @var
     */
    private $expectedValue;


    /**
     * @var
     */
    private $jsonPath;


    /**
     * @var bool
     */
    private $matchAll;



    public function __construct($jsonPath, $expectedValue, $matchAll = FALSE)
    {
        parent::__construct();

        $this->expectedValue = $expectedValue;
        $this->jsonPath      = $jsonPath;
        $this->matchAll      = $matchAll;
    }



    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "contains value '{$this->expectedValue}' at path '{$this->jsonPath}'";
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
            $matches = $combineFunc($matches, $v == $this->expectedValue);
        }

        return $matches;
    }


}