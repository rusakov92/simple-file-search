<?php

namespace App;

use App\IteratorFilter\FileContentIteratorFilter;
use App\IteratorFilter\MinimumDepthIteratorFilter;
use App\IteratorFilter\SkipDirectoryIteratorFilter;
use App\IteratorFilter\FileExtensionIteratorFilter;
use App\IteratorFilter\FileIteratorFilter;
use App\Specification\FileExtensionSpecification;

/**
 * Class SimpleFileSearch.
 *
 * @author Aleksandar Rusakov
 */
class SimpleFileSearch
{
    protected $baseDir;
    protected $dirs = [];
    protected $skipDirs = [];
    protected $minDepth = 0;
    protected $maxDepth = 256;
    protected $extensions = [];
    protected $contains = [];
    private $fileExtensionSpec;

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
            throw new \InvalidArgumentException(\sprintf('Directory "%s" does not exist.', $baseDir));
        }
        $this->baseDir = \realpath($this->baseDir);
    }

    /**
     * Search for files only in specific directories of the base directory.
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
                $this->dirs[$dir] = true;
            } else {
                throw new \InvalidArgumentException(\sprintf('Directory "%s" does not exist.', $dir));
            }
        }

        return $this;
    }

    /**
     * Skip the file search in specific directories of the base directory.
     *
     * @param string[]|string $dirs
     *
     * @return SimpleFileSearch
     *
     * @throws \InvalidArgumentException
     */
    public function skip($dirs) : self
    {
        foreach ((array) $dirs as $dir) {
            $dir = $this->baseDir.\DIRECTORY_SEPARATOR.\trim($dir, \DIRECTORY_SEPARATOR);
            if (\is_dir($dir)) {
                $this->skipDirs[$dir] = true;
            } else {
                throw new \InvalidArgumentException(\sprintf('Directory "%s" does not exist.', $dir));
            }
        }

        return $this;
    }

    /**
     * Set the minimum and maximum depth on the recursion to scan.
     *
     * @param int $min
     * @param int $max
     *
     * @return SimpleFileSearch
     *
     * @throws \InvalidArgumentException
     */
    public function depth(int $min = 0, int $max = 256) : self
    {
        if ($min < 0 || $max < 0) {
            throw new \InvalidArgumentException('The minimum or maximum depth cannot be lower then 0.');
        }

        if ($min > $max) {
            throw new \InvalidArgumentException('The minimum depth cannot bigger then the maximum depth.');
        }

        $this->minDepth = $min;
        $this->maxDepth = $max;

        return $this;
    }

    /**
     * Search for files with specific extension.
     *
     * @param string[]|string $extensions
     *
     * @return SimpleFileSearch
     *
     * @throws \InvalidArgumentException
     */
    public function extension($extensions) : self
    {
        $fileExtensionSpec = $this->getFileExtensionSpecification();
        foreach ((array) $extensions as $extension) {
            $this->extensions[$fileExtensionSpec->isSatisfiedBy($extension)] = true;
        }

        return $this;
    }

    /**
     * Search file by their content that is matching the regular expressions.
     *
     * @param string $regEx
     *
     * @return SimpleFileSearch
     *
     * @throws \InvalidArgumentException
     */
    public function contains(string $regEx) : self
    {
        if (\preg_match('{^#.*#[imsxADSUXJu]*$}', $regEx)) {
            $this->contains[$regEx] = true;
        } else {
            throw new \InvalidArgumentException('Your regular expresion decimeters must be symbol "#".');
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
        $dirs = \array_keys($this->dirs);

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

        // Skip recursively directories from the scanned directory.
        if ($this->skipDirs) {
            $iterator = new SkipDirectoryIteratorFilter($iterator, $this->skipDirs);
        }

        // Recursively iterate over the found files and directories and build a new iterator with all of the items.
        $iterator = new \RecursiveIteratorIterator($iterator);
        $iterator->setMaxDepth($this->maxDepth);

        // Filter out files outside the required min depth.
        if ($this->minDepth > 0) {
            $iterator = new MinimumDepthIteratorFilter($iterator, $this->minDepth);
        }

        // Filter out the directories.
        $iterator = new FileIteratorFilter($iterator);

        // Filter out files that don't have the required extension.
        if ($this->extensions) {
            $iterator = new FileExtensionIteratorFilter($iterator, $this->extensions);
        }

        // Filter out files that don't have the required content.
        if ($this->contains) {
            $iterator = new FileContentIteratorFilter($iterator, \array_keys($this->contains));
        }

        return $iterator;
    }

    /**
     * Lazy load the file extension spec.
     *
     * @return FileExtensionSpecification
     */
    private function getFileExtensionSpecification() : FileExtensionSpecification
    {
        if (null === $this->fileExtensionSpec) {
            $this->fileExtensionSpec = new FileExtensionSpecification();
        }

        return $this->fileExtensionSpec;
    }
}
