<?php

namespace Mzz\MzzBundle\Acl;
use Mzz\MzzBundle\Authentication\Authentication;

class AclManager
{
    /**
     *
     * @var ISpecification $specification
     */
    private $specification;

    public function __construct(ISpecification $specification = null)
    {
        $this->specification = $specification;
    }

    public function setSpecification(ISpecification $specification)
    {
        $this->specification = $specification;
    }

    public function hasPermission(Authentication $auth)
    {
        return $this->specification->isSatisfiedBy($auth);
    }
}
