<?php

namespace Pliny\Console;

use Pliny\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command
{
    /**
     * Setup config.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('build')
            ->setDescription('Build a static site');
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting build...');

        $pliny = new Application();
        $pliny->build();

        $output->writeln('Build complete!');
    }
}
