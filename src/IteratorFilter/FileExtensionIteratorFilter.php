<?php

namespace App\IteratorFilter;

/**
 * Class FileExtensionIteratorFilter.
 *
 * @author Aleksandar Rusakov
 */
class FileExtensionIteratorFilter extends \FilterIterator
{
    protected $extensions;

    /**
     * FileExtensionIteratorFilter constructor.
     *
     * @param \Iterator $iterator
     * @param array     $extensions
     */
    public function __construct(\Iterator $iterator, array $extensions)
    {
        parent::__construct($iterator);
        $this->extensions = $extensions;
        \array_walk($this->extensions, function (&$extension) {
            $extension = \mb_strtolower(\trim($extension, '.'));
        });
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        /** @var \SplFileInfo $file */
        $file = $this->current();
        $fileExtension = $file->getExtension();

        if (empty($fileExtension)) {
            return false;
        }

        return \in_array(\mb_strtolower($fileExtension), $this->extensions, true);
    }
}
