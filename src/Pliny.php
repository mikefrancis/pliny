<?php

namespace Pliny;

use Mni\FrontYAML\Parser;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Pliny
{
    /**
     * Instance of Filesystem.
     */
    private Filesystem $filesystem;

    /**
     * Instance of Parser.
     */
    private Parser $parser;

    /**
     * Instance of Twig.
     */
    private Environment $twig;

    // TODO: Replace this with PHP8 constructor promotion
    private array $config;

    /**
     * Constructor.
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->init();
    }

    /**
     * Initialise Pliny.
     */
    private function init()
    {
        $loader = new FilesystemLoader($this->config['rootDir']);
        $this->twig = new Environment($loader, ['autoescape' => false]);
        $this->filesystem = new Filesystem();
        $this->parser = new Parser();
    }

    /**
     * Start the application.
     */
    public function build()
    {
        $this->cleanUp();

        // Build single pages
        $files = glob($this->config['rootDir'] . '/*.html');

        foreach ($files as $file) {
            $pathParts = pathinfo($file);
            $this->filesystem->dumpFile(
                $this->config['rootDir'] . '/' . $this->config['siteDir'] . '/' . $pathParts['filename'] . '.html',
                $this->twig->render('index.html')
            );
            // $this->buildHtml($file);
        }

        // Build collections
        foreach ($this->config['collections'] as $collection => $data) {
            if (is_string($data)) {
                $collection = $data;
            }

            $this->buildCollection($collection);
        }
    }

    /**
     * Build all item collections.
     */
    private function buildCollection(string $collection)
    {
        $files = glob($this->config['rootDir'] . '/' . $collection . '/*.md');

        foreach ($files as $file) {
            $this->buildHtml($file, $collection);
        }
    }

    /**
     * Transform markdown files into HTML.
     */
    private function buildHtml(string $file, ?string $collection = null)
    {
        $data        = $this->parseFile(file_get_contents($file));
        $pathParts   = pathinfo($file);
        $newFilename = $this->config['rootDir'] .
            '/' . $this->config['siteDir'] .
            ($collection ? '/' . $collection : '') .
            '/' . $pathParts['filename'] . '.html';

        $this->filesystem->dumpFile($newFilename, $this->renderLayout($data));
    }

    /**
     * Parse front-matter/markdown and return as HTML.
     */
    private function parseFile(string $raw)
    {
        $document = $this->parser->parse($raw);
        $yaml = $document->getYAML();

        if (!$yaml) {
            return [];
        }

        $data = array_merge($yaml, ['content' => $document->getContent()]);

        return $data;
    }

    /**
     * Render layout.
     */
    private function renderLayout(array $data)
    {
        // $layout = $data['layout'] ?? $this->config['layoutsDir'] . '/default.html';
        $layout = $data['layout'];

        return $this->twig->render($layout, $data);
    }

    /**
     * Clean up the output files.
     */
    public function cleanUp()
    {
        $sitePath = $this->config['rootDir'] . '/' . $this->config['siteDir'];

        $this->filesystem->remove($sitePath);
        $this->filesystem->mkdir($sitePath);
    }
}
