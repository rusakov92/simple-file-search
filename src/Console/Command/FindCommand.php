<?php

namespace App\Console\Command;

use Symfony\Component\Console\Command\Command;

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
            ->setName('find')
            ->setDescription('Find files by their content.')
            ->setHelp('Run this command to find one or more files that contain a phrase in one or more directories.');
    }
}
