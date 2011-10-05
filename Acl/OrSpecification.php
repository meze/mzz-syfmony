<?php

namespace Mzz\MzzBundle\Acl;
use Mzz\MzzBundle\Authentication\Authentication;

class OrSpecification extends CompositeSpecification
{
    private $leftSpecification;
    private $rightSpecification;

    public function __construct(ISpecification $leftSpecification, ISpecification $rightSpecification)
    {
        $this->leftSpecification = $leftSpecification;
        $this->rightSpecification = $rightSpecification;
    }

    public function isSatisfiedBy(Authentication $candidate)
    {
        return $this->leftSpecification->isSatisfiedBy($candidate) || $this->rightSpecification->isSatisfiedBy($candidate);
    }

}
