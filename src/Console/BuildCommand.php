<?php

namespace Pliny\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Michelf\Markdown;

class BuildCommand extends Command
{
    /**
     * Compiled site directory.
     *
     * @var string
     */
    private $siteDir = '_site';

    /**
     * Instance of Filesystem.
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->filesystem = new Filesystem();
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
        $this->filesystem->mkdir($this->siteDir);

        $filenames = glob('**/*.md');

        foreach ($filenames as $filename) {
            $output->writeln("Writing: $filename");
            $this->buildFile($filename);
        }

        $output->writeln('Build completed!');
    }

    /**
     * Build a HTML file from a markdown file.
     *
     * @param string $filename
     * @return void
     */
    private function buildFile($filename)
    {
        $pathParts   = pathinfo($filename);
        $rawMarkdown = file_get_contents($filename);
        $newFilename = $this->siteDir . '/' . str_replace('_', '', $pathPaths['dirname']) . '/' . $filename . '.html';

        $this->filesystem->dumpFile($newFilename, $this->markdownToHtml($rawMarkdown));
    }

    /**
     * Parse markdown and return as HTML.
     *
     * @param string $rawMarkdown
     * @return string
     */
    private function markdownToHtml($rawMarkdown)
    {
        return Markdown::defaultTransform($rawMarkdown);
    }
}
