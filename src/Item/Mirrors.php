<?php

namespace DmscnEu\SceneOrgFileInfo\Item;

/**
 * Class Mirrors
 */
class Mirrors implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $mirrors;

    /**
     * Mirrors constructor.
     */
    public function __construct()
    {
        $this->mirrors = [];
    }

    /**
     * @param Mirror $mirror
     */
    public function add(Mirror $mirror)
    {
        $this->mirrors[] = $mirror;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->mirrors);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->mirrors);
    }
}
