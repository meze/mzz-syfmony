<?php

namespace Mgp\AppBundle\Acl;
use FOS\UserBundle\Model\UserInterface;

class Acl
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

    public function hasPermission(UserInterface $auth)
    {
        return $this->specification->isSatisfiedBy($auth);
    }
}
