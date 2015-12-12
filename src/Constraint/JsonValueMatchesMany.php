<?php
namespace Helmich\JsonAssert\Constraint;


use PHPUnit_Framework_Constraint as Constraint;


class JsonValueMatchesMany extends Constraint
{



    /** @var JsonValueMatches[] */
    private $constraints = array();



    public function __construct(array $constraints)
    {
        parent::__construct();

        foreach ($constraints as $key => $constraint)
        {
            if (!$constraint instanceof Constraint)
            {
                $constraint = new \PHPUnit_Framework_Constraint_IsEqual($constraint);
            }

            $this->constraints[] = new JsonValueMatches($key, $constraint);
        }
    }


    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return implode(' and ', array_map(function(Constraint $a) { return $a->toString(); }, $this->constraints));
    }



    protected function matches($other)
    {
        foreach ($this->constraints as $constraint)
        {
            if (!$constraint->evaluate($other, '', TRUE)) {
                return FALSE;
            }
        }
        return TRUE;
    }


}