<?php

namespace Mzz\MzzBundle\Acl;
use FOS\UserBundle\Model\UserInterface;

class UserHasRoleSpecification extends CompositeSpecification
{
    const ANONYMOUS = 'ANONYMOUS';
    const ALL = 'ALL';

    private $roles = array();

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function isSatisfiedBy(UserInterface $candidate)
    {
        return $this->isAllowedForAll() || $this->hasAtLeastOneOfRequiredRole($candidate->getRoles());
    }

    private function hasAtLeastOneOfRequiredRole(array $authorities)
    {
        return count(\array_intersect($this->roles, $authorities)) > 0;
    }

    private function isAllowedForAll()
    {
        return $this->roles === array(self::ALL);
    }
}