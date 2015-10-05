<?php

namespace Pliny;

use Mni\FrontYAML\Parser;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Application
{
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
    private $templateDir = '_layouts';

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
     * Config for templating engine.
     *
     * @var array
     */
    private $layoutConfig = [
        'autoescape' => false
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->parser     = new Parser();

        $this->start();
    }

    /**
     * Start the application.
     *
     * @return void
     */
    public function start()
    {
        $this->cleanUp();

        $filenames = glob('**/*.md');

        foreach ($filenames as $filename) {
            $this->buildHtml($filename);
        }
    }

    /**
     * Clean up the output files.
     *
     * @return void
     */
    private function cleanUp()
    {
        $this->filesystem->remove($this->siteDir);

        $this->filesystem->mkdir($this->siteDir);
    }

    /**
     * Tranform markdown files into HTML.
     *
     * @param string $filename
     * @return void
     */
    private function buildHtml($filename)
    {
        $newFilename = $this->createNewFilename($filename);
        $data        = $this->parseFile(file_get_contents($filename));

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
        $loader = new Twig_Loader_Filesystem($this->templateDir);
        $twig   = new Twig_Environment($loader, $this->layoutConfig);

        return $twig->render('default.html', $data);
    }

    /**
     * Create a new filename.
     *
     * @param $filename string
     * @return string
     */
    private function createNewFilename($filename)
    {
        $pathParts = pathinfo($filename);
        $filename  = $this->siteDir . '/' .
            str_replace('_', '', $pathPaths['dirname']) .
            '/' . $pathParts['filename'] . '.html';

        return $filename;
    }
}
