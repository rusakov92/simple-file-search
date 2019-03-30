<?php

namespace App;

use App\IteratorFilter\FileIteratorFilter;

/**
 * Class SimpleFileSearch.
 *
 * @author Aleksandar Rusakov
 */
class SimpleFileSearch
{
    protected $baseDir;
    protected $dirs = [];

    /**
     * SimpleFileSearch constructor.
     *
     * @param string $baseDir Specify a directory root.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $baseDir)
    {
        $this->baseDir = \rtrim($baseDir, \DIRECTORY_SEPARATOR);
        if (\is_dir($baseDir) === false) {
            throw new \InvalidArgumentException(\sprintf('Directory %s does not exist.', $baseDir));
        }
    }

    /**
     * Search for files only in specific directories of the base root directory.
     *
     * @param string[]|string $dirs
     *
     * @return SimpleFileSearch
     *
     * @throws \InvalidArgumentException
     */
    public function in($dirs) : self
    {
        foreach ((array) $dirs as $dir) {
            $dir = $this->baseDir.\DIRECTORY_SEPARATOR.\trim($dir, \DIRECTORY_SEPARATOR);
            if (\is_dir($dir)) {
                $this->dirs[] = $dir;
            } else {
                throw new \InvalidArgumentException(\sprintf('Directory %s does not exist.', $dir));
            }
        }

        return $this;
    }

    /**
     * Try finding something from the set rules till now.
     *
     * @return \Iterator
     */
    public function find() : \Iterator
    {
        $dirs = $this->dirs;

        if (empty($dirs)) {
            $dirs[] = $this->baseDir;
        }

        if (\count($dirs) === 1) {
            return $this->search(\reset($dirs));
        }

        $files = [];
        foreach ($dirs as $dir) {
            /** @var \SplFileInfo $item */
            foreach ($this->search($dir) as $item) {
                $files[$item->getPath()] = $item;
            }
        }

        return new \ArrayIterator(\array_values($files));
    }

    /**
     * Do a recursive search of the given directory and find all files using the rules set till now.
     *
     * @param string $dir
     *
     * @return \Iterator
     */
    private function search(string $dir) : \Iterator
    {
        // Do a recursive scan on the given directory.
        $iterator = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);

        // Exclude directories from the scanned directory.

        // Recursively iterate over the found files and directories and build a new iterator with all of the items.
        $iterator = new \RecursiveIteratorIterator($iterator);

        // Filter out the directories.
        $iterator = new FileIteratorFilter($iterator);

        // Filter out files that don't have the required extension.

        // Filter out files outside the required depths.

        // Filter out files that don't have the required content.

        return $iterator;
    }
}
