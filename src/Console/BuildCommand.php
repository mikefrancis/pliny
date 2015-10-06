<?php

namespace Pliny\Console;

use Pliny\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

class BuildCommand extends Command
{
    /**
     * Configuration.
     *
     * @var array
     */
    private $config;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $yaml = new Parser();
        $this->config = $yaml->parse(file_get_contents(getcwd() . '/_config.yml'));
    }

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

        $pliny = new Application($this->config);
        $pliny->build();

        $output->writeln('<info>Build complete!</info>');
    }
}
