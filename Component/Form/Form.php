<?php

namespace Mzz\MzzBundle\Component\Form;

use Symfony\Component\Form\Form as SymfonyForm;

class Form extends SymfonyForm
{

    public function getErrors()
    {
        return $this->getAllErrors();
    }

    public function hasErrors()
    {
        $errors = $this->getErrors();
        return !empty($errors);
    }

    private function getAllErrors()
    {
        $errors = parent::getErrors();

        foreach ($this->fields as $field)
            $errors = \array_merge($field->getErrors(), $errors);
        return $errors;
    }

    public function errors()
    {
        return $this->getErrors();
    }
}