<?php

namespace Demo\Console\Command;

use App\SimpleFileSearch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FindCommand.
 *
 * @author Aleksandar Rusakov
 */
class FindCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('demo:search')
            ->setDescription('Search for files by their content.')
            ->setHelp('This command demonstrates how the application work by finding one or more files that contain a phrase in one or more directories by looking at the files in the demo folder located in the public folder.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileSearch = new SimpleFileSearch(__DIR__.'/../../../public/demo_files');
        $a = $fileSearch->in('dir1')->in('dir2')->extension('txt')->find();
        foreach ($a as $item) {
            dump($item);
        }
        die;

//        $fileSearch->in('/demo_files_1')->depth('< 3')->content('asd')->type('txt')->find();
    }
}
