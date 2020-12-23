<?php

namespace Pliny\Console;

use Pliny\Pliny;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Parser;

class BuildCommand extends Command
{
    private array $defaultConfig = [
        'rootDir' => '.',
        'siteDir' => 'public',
        // 'layoutsDir' => '_layouts',
        'collections' => [],
    ];

    /**
     * Setup config.
     */
    protected function configure()
    {
        $this->setName('build')
            ->setDescription('Build a static site')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('config', 'c', InputOption::VALUE_OPTIONAL, 'Path to YAML config file'),
                    new InputOption('rootDir', 'r', InputOption::VALUE_OPTIONAL, 'Root directory of site to build'),
                    new InputOption('siteDir', 's', InputOption::VALUE_OPTIONAL, 'Target directory for build'),
                    // new InputOption('layoutsDir', 'l', InputOption::VALUE_OPTIONAL, 'Directory containing layouts'),
                    new InputOption('collections', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY),
                ])
            );
    }

    /**
     * Execute the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting build...');

        $config = $this->defaultConfig;

        if ($input->getOption('config')) {
            $yaml = new Parser();

            $config = array_merge($config, $yaml->parse(file_get_contents($input->getOption('config'))));
        }

        $config = [
            'rootDir' => $input->getOption('rootDir') ?? $config['rootDir'],
            'siteDir' => $input->getOption('siteDir') ?? $config['siteDir'],
            // 'layoutsDir' => $input->getOption('layoutsDir') ?? $config['layoutsDir'],
            'collections' => $input->getOption('collections') ?? $config['collections'],
        ];

        $pliny = new Pliny($config);
        $pliny->build();

        $output->writeln('<info>Build complete!</info>');

        return Command::SUCCESS;
    }
}
