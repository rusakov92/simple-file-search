<?php

namespace App\IteratorFilter;

/**
 * Class MinimumDepthIteratorFilter starts the file filtering from the minimum given depth.
 *
 * @author Aleksandar Rusakov
 */
class MinimumDepthIteratorFilter extends \FilterIterator
{
    protected $depth;

    /**
     * {@inheritdoc}
     *
     * @param int $depth
     */
    public function __construct(\RecursiveIteratorIterator $iterator, int $depth)
    {
        parent::__construct($iterator);

        $this->depth = $depth;
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        /** @var \RecursiveIteratorIterator $innerIterator */
        $innerIterator = $this->getInnerIterator();

        return $innerIterator->getDepth() >= $this->depth;
    }
}
