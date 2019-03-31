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
    public function receive_an_exception_when_skipped_directory_is_not_a_dir()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);

        $this->expectException(\InvalidArgumentException::class);
        $obj->skip('non_existing_dir');
    }

    /**
     * @test
     */
    public function skip_some_of_the_directories()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);
        $result = $obj->in('dir1')->skip('dir1/dir1-2')->extension('txt')->find();
        $this->assertCount(3, $result);
        /** @var \SplFileInfo $item */
        foreach ($result as $item) {
            $this->assertNotEquals('test1-2.txt', $item->getFilename());
        }
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

    /**
     * @test
     */
    public function receive_exception_when_the_minimum_and_maximum_depth_are_under_one()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);

        try {
            $obj->depth(-1);
            $this->assertTrue(false);
        } catch (\InvalidArgumentException $exception) {
            $this->assertTrue(true);
        }

        try {
            $obj->depth(1, 0);
            $this->assertTrue(false);
        } catch (\InvalidArgumentException $exception) {
            $this->assertTrue(true);
        }

        try {
            $obj->depth(2, 1);
            $this->assertTrue(false);
        } catch (\InvalidArgumentException $exception) {
            $this->assertTrue(true);
        }
    }

    /**
     * @test
     */
    public function search_recursivly_till_the_required_depth()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);

        $obj->extension('txt');
        $this->assertCount(7, $obj->find());
        $this->assertCount(7, $obj->depth(0, 3)->find());
        $this->assertCount(6, $obj->depth(0, 2)->find());
        $this->assertCount(4, $obj->depth(0, 1)->find());
        $this->assertCount(2, $obj->depth(0, 0)->find());
        $this->assertCount(5, $obj->depth(1, 3)->find());
        $this->assertCount(3, $obj->depth(2, 3)->find());
        $this->assertCount(1, $obj->depth(3, 3)->find());
    }

    /**
     * @test
     */
    public function receive_exception_when_the_regular_expresion_is_not_valid()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);

        $this->expectException(\InvalidArgumentException::class);
        $obj->contains('not_a_reg_ex');
    }

    /**
     * @test
     */
    public function search_for_files_that_cotain_the_given_reg_ex()
    {
        $obj = new SimpleFileSearch(self::TEST_DIR);

        $result = $obj->contains('#test sentence#')->extension('txt')->find();
        $this->assertCount(2, $result);
        /** @var \SplFileInfo[] $result */
        $result = \array_values(\iterator_to_array($result));
        $this->assertEquals('test1.txt', $result[0]->getFilename());
        $this->assertEquals('test1-1-1.txt', $result[1]->getFilename());
    }
}
