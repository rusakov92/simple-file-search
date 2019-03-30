<?php

namespace App\IteratorFilter;

/**
 * Class FileIteratorFilter will filter anything that is not a file.
 *
 * @author Aleksandar Rusakov
 */
class FileIteratorFilter extends \FilterIterator
{
    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        /** @var \SplFileInfo $file */
        $file = $this->current();

        return $file->isFile();
    }
}
