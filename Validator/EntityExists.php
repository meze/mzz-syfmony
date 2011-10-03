<?php

namespace Mzz\MzzBundle\Validator;

use Symfony\Component\Validator\Constraint;

class EntityExists extends Constraint
{
    public $message = '%s is not found.';
    public $entityDesc = 'Entity';
    public $entity;

    public function validatedBy()
    {
        return 'mzz.validator.entityexists';
    }

    public function targets()
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }

    public function requiredOptions()
    {
        return array('entity');
    }
}
