<?php

namespace App\SymfonyBundle\Command;

use App\SimpleFileSearch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
            ->setName('search')
            ->setDescription('Search for files by their content')
            ->setHelp(
                'This command demonstrates how the application work by finding one or more files that contain a phrase in one or more directories by looking at the files in the demo folder located in the public folder'
            )
            ->addArgument(
                'content',
                InputArgument::IS_ARRAY|InputArgument::REQUIRED,
                'Pass one or more regular expresion or a strings to search files by their content'
            )
            ->addOption(
                'extension',
                'E',
                InputOption::VALUE_IS_ARRAY|InputOption::VALUE_OPTIONAL,
                'Specify what file extensions to look for',
                []
            )
            ->addOption(
                'min-depth',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Specify the minimum depth to start the file search from',
                0
            )
            ->addOption(
                'max-depth',
                'D',
                InputOption::VALUE_OPTIONAL,
                'Specify the maximum depth to end the file search to',
                256
            )
            ->addOption(
                'in',
                'i',
                InputOption::VALUE_IS_ARRAY|InputOption::VALUE_OPTIONAL,
                'Search in specific folders',
                []
            )
            ->addOption(
                'skip',
                's',
                InputOption::VALUE_IS_ARRAY|InputOption::VALUE_OPTIONAL,
                'Skip specific folders',
                []
            )
            ->addUsage('abc To find files that contain the sentence "abc"')
            ->addUsage('#abc# #cba# To find files that contain the sentences "abc" or "cba"')
            ->addUsage('abc -e txt To find files that contain "abc" and are extension is .txt')
            ->addUsage('abc -d 3 To find files that contain "abc" and are in the 3rd folder only')
            ->addUsage('abc -D 9 To find files that contain "abc" and are in maximum 9th folder only')
            ->addUsage('abc -i path/to/dir To find files that contain "abc" and are in specific folder')
            ->addUsage('abc -s path/to/dir To find files that contain "abc" and are not in the specified folder')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contentToSearch = $input->getArgument('content');
        \array_walk($contentToSearch, function (&$content) {
            if (\preg_match('{^#.*#[imsxADSUXJu]*$}', $content) !== 1) {
                $content = \sprintf('#%s#', $content);
            }
        });

        $fileSearch = new SimpleFileSearch(__DIR__.'/../../../../public/demo_files');
        $result = $fileSearch
            ->contains($contentToSearch)
            ->extension($input->getOption('extension'))
            ->depth($input->getOption('min-depth'), $input->getOption('max-depth'))
            ->in($input->getOption('in'))
            ->skip($input->getOption('skip'))
            ->find();

        $result = \iterator_to_array($result);
        if (empty($result)) {
            $output->writeln('<info>No files found!</info>');

            return 0;
        }

        $output->writeln('<info>Here are the files we found:</info>');
        /** @var \SplFileInfo $item */
        foreach ($result as $item) {
            $output->writeln($item->getRealPath());
        }

        return 0;
    }
}
