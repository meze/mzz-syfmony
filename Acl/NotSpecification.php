<?php

namespace Mzz\MzzBundle\Acl;
use Mzz\MzzBundle\Authentication\Authentication;

class NotSpecification extends CompositeSpecification
{
    private $innerSpecification;

    public function __construct(ISpecification $innerSpecification)
    {
        $this->innerSpecification = $innerSpecification;
    }

    public function isSatisfiedBy(Authentication $candidate)
    {
        return !($this->innerSpecification->isSatisfiedBy($candidate));
    }

}
