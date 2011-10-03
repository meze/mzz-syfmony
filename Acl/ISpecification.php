<?php

namespace Mzz\MzzBundle\Acl;
use Mzz\MzzBundle\Authentication\Authentication;

interface ISpecification
{
    function isSatisfiedBy(Authentication $candidate);
    function logicalAnd(ISpecification $other);
    function logicalOr(ISpecification $other);
    function logicalNot();
}