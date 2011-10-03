<?php

namespace Mzz\MzzBundle\Acl;
use Mzz\MzzBundle\Authentication\Authentication;

abstract class CompositeSpecification implements ISpecification
{
    public function logicalAnd(ISpecification $other)
    {
        return new AndSpecification($this, $other);
    }

    public function logicalOr(ISpecification $other)
    {
        return new OrSpecification($this, $other);
    }

    public function logicalNot()
    {
        return new NotSpecification($this);
    }
}