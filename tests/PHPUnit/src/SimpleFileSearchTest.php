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
    const TEST_DIR = __DIR__.'/../test_case/test_files';

    /**
     * @test
     */
    public function receive_exception_when_base_dir_is_not_a_dir()
    {
        $this->expectException(\InvalidArgumentException::class);
        new SimpleFileSearch('not_a_dir');
    }

    /**
     * @test
     */
    public function receive_exception_when_filtered_directory_is_no_a_dir()
    {
        $this->expectException(\InvalidArgumentException::class);
        $obj = new SimpleFileSearch(self::TEST_DIR);
        $obj->in(['dir1', 'dir2', 'not_a_dir']);
    }

    /**
     * @test
     */
    public function find_file_from_specific_directories()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);

        $obj->in('dir1/dir1-1/dir1-1-1');
        $this->assertCount(1, $obj->find());

        $obj->in('dir1/dir1-1');
        $this->assertCount(2, $obj->find());

        $obj->in('dir1/dir1-2');
        $this->assertCount(3, $obj->find());

        $obj->in('dir1');
        $this->assertCount(4, $obj->find());
    }

    /**
     * @test
     */
    public function find_all_files_from_the_base_dir_if_no_specific_directiries_are_set()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);

        $this->assertCount(7, $obj->find());
    }
}
