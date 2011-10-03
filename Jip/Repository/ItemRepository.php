<?php

namespace Mzz\MzzBundle\Jip\Repository;

interface ItemRepository
{
    public function findAll();
    public function findByName($name);
}
