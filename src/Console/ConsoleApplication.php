<?php

namespace App\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleApplication.
 *
 * @author Aleksandar Rusakov
 */
class ConsoleApplication extends Application
{
    /**
     * {@inheritdoc}
     *
     * Default to force decoration on the output.
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $output->setDecorated(true);
        if (true === $input->hasParameterOption('--no-ansi', true)) {
            $output->setDecorated(false);
        }

        return parent::doRun($input, $output);
    }
}
