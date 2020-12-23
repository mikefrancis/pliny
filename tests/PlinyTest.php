<?php

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Pliny\Application;
use Pliny\Pliny;

class PlinyTest extends TestCase
{
    /**
     * Check Pliny can be instantiated.
     */
    public function testPlinyCanBeBuild()
    {
        $structure = [
            'examples' => [
                'test.php'    => 'some text content',
                'other.php'   => 'Some more text content',
                'Invalid.csv' => 'Something else',
            ],
            'an_empty_folder' => [],
            'badlocation.php' => 'some bad content',
            '[Foo]'           => 'a block device',
        ];
        $root = vfsStream::setup('root', null, $structure);

        $app = new Pliny([
            'rootDir' => 'somewhere',
            'siteDir' => 'www',
            'collections' => 'elephants',
        ]);
    }

    /**
     * Check pliny can build.
     */
    public function testItBuildsSite()
    {
        $this->pliny->build();
    }
}
