<?php

namespace Mgp\AppBundle\Acl;
use Mgp\AppBundle\Document\Site;
use Mgp\AppBundle\Document\Organization;
use FOS\UserBundle\Model\UserInterface;
use Mgp\AppBundle\Acl\OrSpecification;


class ApplicationSpecification extends CompositeSpecification
{
    private $site;
    private $requiredRole;
    private $organization;

    public function __construct($object, $required_role)
    {
        if ($object instanceof Site) {
            $this->site = $object;
            $this->organization = $object->getOrganization();
        } elseif ($object instanceof Organization) {
            $this->organization = $object;
        } elseif ($object !== null) {
            throw new \RuntimeException('Unknown argument type (only instance of Site or Organization or null are allowed).');
        }
        if (is_scalar($required_role)) {
            $required_role = array($required_role);
        }
        $this->requiredRole = $required_role;
    }

    public function isSatisfiedBy(UserInterface $candidate)
    {
        $user_site_spec = new UserBelongsToSiteSpecification($this->site);
        $user_site_spec = $user_site_spec->logicalAnd(new UserHasRoleSpecification(array($this->requiredRole)));

        $user_org_spec = new UserBelongsToOrganizationSpecification($this->organization);
        $user_org_spec = $user_org_spec->logicalAnd(new UserHasRoleSpecification($this->requiredRole));

        $admin_spec = $user_site_spec->logicalOr($user_org_spec);

        $super_admin_spec = new UserHasRoleSpecification(array('ROLE_SUPER_ADMIN'));

        return $admin_spec->logicalOr($super_admin_spec)->isSatisfiedBy($candidate);
    }

}
