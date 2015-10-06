<?php

use Pliny\Application;

class PlinyTest extends PHPUnit_Framework_TestCase
{
    /**
     * Instance of Pliny Application.
     *
     * @var Application
     */
    private $pliny;

    /**
     * Instantiate a new Pliny Application.
     *
     * @return void
     */
    public function setUp()
    {
        $this->pliny = new Application([
            'root'        => __DIR__,
            'layouts'     => '_layouts',
            'collections' => [
                'posts'
            ]
        ]);
    }

    /**
     * Check Pliny can be instantiated.
     */
    public function testPlinyCanBeInstantiated()
    {
        $this->assertInstanceOf(Application::class, $this->pliny);
    }

    /**
     * Check pliny can build.
     */
    public function testItBuildsSite()
    {
        $this->pliny->build();
    }
}
