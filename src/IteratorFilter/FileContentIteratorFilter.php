<?php

namespace App\IteratorFilter;

/**
 * Class FileContentIteratorFilter will filter files the match the given regular expressions.
 *
 * @author Aleksandar Rusakov
 */
class FileContentIteratorFilter extends \FilterIterator
{
    protected $regExs;

    /**
     * {@inheritdoc}
     *
     * @param string[] $regExs
     */
    public function __construct($iterator, array $regExs)
    {
        parent::__construct($iterator);
        $this->regExs = $regExs;
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        /** @var \SplFileInfo $file */
        $file = $this->current();

        if ($file->isDir() || $file->isReadable() === false) {
            return false;
        }

        $content = \file_get_contents($file->getPathname());
        if (empty($content)) {
            return false;
        }

        foreach ($this->regExs as $regEx) {
            if (\preg_match($regEx, $content)) {
                return true;
            }
        }

        return false;
    }
}
