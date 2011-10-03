<?php

namespace Mzz\MzzBundle\Jip;

/**
 * Reads the name of the jip menu for a class that is
 * defined in the annotations.
 *
 * Doctrine2's implementation isn't used because for our requirements it's too complicated
 * and might be a bit slow.
 *
 */
class MenuNameAnnotationReader
{
    private $class;

    public function __construct($class)
    {
        $this->class = \is_object($class) ? get_class($class) : $class;
    }

    public function getMenuName()
    {
        $reflection = new \ReflectionClass($this->class);
        return $this->extractMenuName($reflection->getDocComment());
    }

    private function extractMenuName($comment)
    {
        $comments = strtolower($comment);
        $jip_mask = '@jip(';
        $first_position = \strpos($comment, $jip_mask);

        if ($first_position === false)
            return;
        $jip_comment = substr($comment, $first_position + strlen($jip_mask));

        return trim(substr($jip_comment, 0, strpos($jip_comment, ')')), '"\' ');
    }

}
