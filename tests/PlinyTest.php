<?php

use Pliny\Application;

class PlinyTest extends PHPUnit_Framework_TestCase
{
    public function testPlinyCanBeIntantiated()
    {
        $pliny = new Application();

        $this->assertInstanceOf($pliny, Application::class);
    }
}
