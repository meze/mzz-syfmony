<?php

namespace Mzz\MzzBundle\Acl;
use FOS\UserBundle\Model\UserInterface;

class NotSpecification extends CompositeSpecification
{
    private $innerSpecification;

    public function __construct(ISpecification $innerSpecification)
    {
        $this->innerSpecification = $innerSpecification;
    }

    public function isSatisfiedBy(UserInterface $candidate)
    {
        return !($this->innerSpecification->isSatisfiedBy($candidate));
    }

}
