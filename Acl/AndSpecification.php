<?php

namespace Mzz\MzzBundle\Acl;
use FOS\UserBundle\Model\UserInterface;

class AndSpecification extends CompositeSpecification
{
    private $leftSpecification;
    private $rightSpecification;

    public function __construct(ISpecification $leftSpecification, ISpecification $rightSpecification)
    {
        $this->leftSpecification = $leftSpecification;
        $this->rightSpecification = $rightSpecification;
    }

    public function isSatisfiedBy(UserInterface $candidate)
    {
        return $this->leftSpecification->isSatisfiedBy($candidate) && $this->rightSpecification->isSatisfiedBy($candidate);
    }

}
