<?php

namespace Mzz\MzzBundle\Acl;
use FOS\UserBundle\Model\UserInterface;

interface ISpecification
{
    function isSatisfiedBy(UserInterface $candidate);
    function logicalAnd(ISpecification $other);
    function logicalOr(ISpecification $other);
    function logicalNot();
}