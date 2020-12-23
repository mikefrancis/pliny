<?php

namespace Pliny\Console;

use Pliny\Pliny;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

class CleanCommand extends Command
{
    /**
     * Setup config.
     */
    protected function configure()
    {
        $this->setName('clean')
            ->setDescription('Delete the output folder');
    }

    /**
     * Execute the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Cleaning...');

        $pliny = new Pliny($config);
        $pliny->clean();

        $output->writeln('<info>Clean complete!</info>');

        return Command::SUCCESS;
    }
}
