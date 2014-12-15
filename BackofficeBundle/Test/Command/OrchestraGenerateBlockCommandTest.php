<?php

namespace PHPOrchestra\BackofficeBundle\Test\Command;

use Phake;
use PHPOrchestra\BackofficeBundle\Command\OrchestraGenerateBlockCommand;
use Symfony\Component\Console\Application;

/**
 * Class OrchestraGenerateBlockCommandTest
 */
class OrchestraGenerateBlockCommandTest extends \PHPUnit_Framework_TestCase
{
    protected $command;
    protected $container;
    protected $application;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->container = $this->container = Phake::mock('Symfony\Component\DependencyInjection\Container');

        $this->command = new OrchestraGenerateBlockCommand();
        $this->command->setContainer($this->container);

        $this->application = new Application();
        $this->application->add($this->command);
    }

    /**
     * Test presence and name
     */
    public function testPresenceAndName()
    {
        $command = $this->application->find('orchestra:generate:block');

        $this->assertInstanceOf('Symfony\Component\Console\Command\Command', $command);
    }

    /**
     * Test the definition
     */
    public function testDefinition()
    {
        $definition = $this->command->getDefinition();

        $this->assertTrue($definition->hasOption('block-name'));
        $this->assertTrue($definition->hasOption('form-generator-dir'));
        $this->assertTrue($definition->hasOption('form-generator-conf'));
        $this->assertTrue($definition->hasOption('front-display-dir'));
        $this->assertTrue($definition->hasOption('front-display-conf'));
        $this->assertTrue($definition->hasOption('backoffice-icon-dir'));
        $this->assertTrue($definition->hasOption('backoffice-icon-conf'));
        $this->assertTrue($definition->hasOption('backoffice-display-dir'));
        $this->assertTrue($definition->hasOption('backoffice-display-conf'));
    }
}
