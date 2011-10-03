<?php

namespace Mzz\MzzBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntityExistsValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct($em)
    {
        $this->entityManager = $em;
    }

    public function isValid($value, Constraint $constraint)
    {
        if (empty($value)) {
            return true;
        }

        if(!$this->entityManager->find($constraint->entity, (int)$value)) {
            $this->setMessage(sprintf($constraint->message, $constraint->entityDesc));
            return false;
        }

        return true;
    }
}
