<?php

namespace App\IteratorFilter;

/**
 * Class SkipDirectoryIteratorFilter filters out any files or directories that have the exact path as one of keys in
 * the exclude array.
 *
 * @author Aleksandar Rusakov
 */
class SkipDirectoryIteratorFilter extends \FilterIterator implements \RecursiveIterator
{
    protected $skip;

    /**
     * {@inheritdoc}
     *
     * @param array $skip
     */
    public function __construct(\Iterator $iterator, array $skip)
    {
        parent::__construct($iterator);
        $this->skip = $skip;
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        /** @var \SplFileInfo $dir */
        $dir = $this->current();

        return isset($this->skip[$dir->getPath()]) === false;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        /** @var \RecursiveDirectoryIterator $innerIterator */
        $innerIterator = $this->getInnerIterator();

        return $innerIterator->hasChildren();
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        /** @var \RecursiveDirectoryIterator $innerIterator */
        $innerIterator = $this->getInnerIterator();
        /** @var \Iterator $children */
        $children = $innerIterator->getChildren();

        return new self($children, $this->skip);
    }
}
