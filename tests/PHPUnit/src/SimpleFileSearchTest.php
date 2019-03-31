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
    public const TEST_DIR = __DIR__.'/../test_case/test_files';

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

        $this->assertCount(8, $obj->find());
    }

    /**
     * @test
     */
    public function receive_an_exception_when_an_unsupported_extension_is_given()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);

        $this->expectException(\InvalidArgumentException::class);
        $obj->extension('unsupported_file_extension');
    }

    /**
     * @test
     */
    public function ignore_unsuported_file_extensions()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);
        $result = $obj->in('dir2')->extension('txt')->find();
        $this->assertCount(1, $result);
        /** @var \SplFileInfo $item */
        foreach ($result as $item) {
            $this->assertEquals('test2.txt', $item->getFilename());
        }
    }
}
