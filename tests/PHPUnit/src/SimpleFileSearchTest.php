<?php

namespace PHPUnit;

use App\SimpleFileSearch;
use PHPUnit\TestCase\TestCase;

/**
 * @SuppressWarnings(PHPMD)
 */
class SimpleFileSearchTest extends TestCase
{
    // phpcs:ignoreFile
    public function test()
    {
        new SimpleFileSearch();
        $this->assertTrue(true);
    }
}
