<?php

namespace App\IteratorFilter;

/**
 * Class FileExtensionIteratorFilter filters out any file that doesn't have the required extension.
 *
 * @author Aleksandar Rusakov
 */
class FileExtensionIteratorFilter extends \FilterIterator
{
    protected $extensions;

    /**
     * {@inheritdoc}
     *
     * @param array $extensions
     */
    public function __construct(\Iterator $iterator, array $extensions)
    {
        parent::__construct($iterator);
        $this->extensions = $extensions;
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

        return isset($this->extensions[\mb_strtolower($fileExtension)]);
    }
}
