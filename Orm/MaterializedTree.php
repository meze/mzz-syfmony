<?php

namespace Mzz\MzzBundle\Orm;

/**
 * @orm:MappedSuperclass
 */
abstract class MaterializedTree
{
    const PATH_DELIMITER = '/';

    /**
     * @orm:Column(type="integer")
     * @var integer $level
     */
    protected $level = 0;

    /**
     * @orm:Column(type="integer")
     * @var integer $parent
     */
    protected $parent = 0;

    /**
     * @orm:Column(type="string")
     * @var string $path
     */
    protected $path;

    abstract function getIdentity();

    public function getParent()
    {
        return $this->parent;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function addChild(MaterializedTree $tree)
    {
        if (empty($this->path))
            throw new \InvalidArgumentException('Parent must have a path');
        if (empty($tree->path))
            throw new \InvalidArgumentException('Child must have a path');

        $tree->appendPath($this->getPath());
        $tree->calculateNewLevel();
        $tree->calculateNewParent();
    }

    protected function appendPath($path)
    {
        $this->setPath(sprintf("%s%s%s", $path, static::PATH_DELIMITER, $this->getPath()));
    }

    protected function calculateNewLevel()
    {
        $this->level = substr_count($this->getPath(), static::PATH_DELIMITER);
    }

    protected function calculateNewParent()
    {
        if (preg_match('~(\d+)/\d+$~', $this->getPath(), $matches))
            $this->parent = $matches[1];
    }
}