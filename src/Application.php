<?php

namespace Pliny;

use Mni\FrontYAML\Parser;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Application
{
    /**
     * Instance of Filesystem.
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Instance of Parser.
     *
     * @var Parser
     */
    private $parser;

    /**
     * Instance of Twig.
     *
     * @var Twig_Environment
     */
    private $twig;

    /**
     * Root directory.
     *
     * @var string
     */
    private $root = '.';

    /**
     * Compiled site directory.
     *
     * @var string
     */
    private $siteDir = 'public';

    /**
     * Template directories.
     *
     * @var string
     */
    private $layoutsDir = '_layouts';

    /**
     * Collections of items used.
     *
     * @var array
     */
    private $collections = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->root        = $config['root'] ? $config['root'] : $this->root;
        $this->siteDir     = $config['site'] ? $config['site'] : $this->siteDir;
        $this->layoutsDir  = $config['layouts'] ? $config['layouts'] : $this->layoutsDir;
        $this->collections = $config['collections'] ? $config['collections'] : $this->collections;

        $loader            = new Twig_Loader_Filesystem($this->root . '/' . $this->layoutsDir);
        $this->twig        = new Twig_Environment($loader, ['autoescape' => false]);
        $this->filesystem  = new Filesystem();
        $this->parser      = new Parser();
    }

    /**
     * Start the application.
     *
     * @return void
     */
    public function build()
    {
        $this->cleanUp();

        foreach ($this->collections as $collection => $data) {
            if (is_string($data)) {
                $collection = $data;
            }

            $this->buildCollection($collection);
        }
    }

    /**
     * Clean up the output files.
     *
     * @return void
     */
    private function cleanUp()
    {
        $this->filesystem->remove($this->root . '/' . $this->siteDir);

        $this->filesystem->mkdir($this->root . '/' . $this->siteDir);
    }

    /**
     * Build all item collections.
     *
     * @param string $collection
     * @return void
     */
    private function buildCollection($collection)
    {
        $files = glob($this->root . '/_' . $collection . '/*.md');

        foreach ($files as $file) {
            $this->buildHtml($collection, $file);
        }
    }

    /**
     * Tranform markdown files into HTML.
     *
     * @param string $collection
     * @param string $file
     * @return void
     */
    private function buildHtml($collection, $file)
    {
        $data        = $this->parseFile(file_get_contents($file));
        $pathParts   = pathinfo($file);
        $newFilename = $this->root .
            '/' . $this->siteDir .
            '/' . $collection .
            '/' . $pathParts['filename'] . '.html';

        $this->filesystem->dumpFile($newFilename, $this->renderLayout($data));
    }

    /**
     * Parse front-matter/markdown and return as HTML.
     *
     * @param string $raw
     * @return array
     */
    private function parseFile($raw)
    {
        $document = $this->parser->parse($raw);

        $data = $document->getYAML() + [
            'content' => $document->getContent()
        ];

        return $data;
    }

    /**
     * Render layout.
     *
     * @param array $data
     * @return string
     */
    private function renderLayout($data)
    {
        $layout = $data['layout'] ? $data['layout'] : 'default.html';

        return $this->twig->render($layout, $data);
    }
}
