<?php

namespace PHPOrchestra\BackofficeBundle\Test\DependencyInjection\Compiler;

use Phake;
use PHPOrchestra\BackofficeBundle\DependencyInjection\Compiler\TinymceCompilerPass;

/**
 * Class TinymceCompilerPassTest
 */
class TinymceCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    protected $compiler;
    protected $tinymce;
    protected $container;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->tinymce = Phake::mock('Stfalcon\Bundle\TinymceBundle\DependencyInjection\StfalconTinymceExtension');
        $this->container = Phake::mock('Symfony\Component\DependencyInjection\ContainerBuilder');

        $this->compiler = new TinymceCompilerPass();
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface', $this->compiler);
    }

    /**
     * Test with definition
     *
     * @param array $param
     *
     * @dataProvider parameterWithDefProvider
     */
    public function testWithDefinition($param)
    {
        Phake::when($this->container)->hasDefinition(Phake::anyParameters())->thenReturn(true);
        Phake::when($this->container)->getParameter('stfalcon_tinymce.config')->thenReturn($param);

        $this->compiler->process($this->container);

        Phake::verify($this->container)->getParameter('stfalcon_tinymce.config');
        Phake::verify($this->container)->setParameter('stfalcon_tinymce.config', $param);
    }

    /**
     * Test with definition
     *
     * @param array $param
     *
     * @dataProvider parameterNoDefProvider
     */
    public function testNoDefinition($param)
    {
        Phake::when($this->container)->hasDefinition(Phake::anyParameters())->thenReturn(false);

        $this->compiler->process($this->container);

        Phake::verify($this->container, Phake::never())->getParameter('stfalcon_tinymce.config');
        Phake::verify($this->container, Phake::never())->setParameter('stfalcon_tinymce.config', $param);
    }

    /**
     * @return array
     */
    public function parameterWithDefProvider()
    {
        return array(
            array(
                array(
                    'tinymce_jquery' => false,
                    'include_jquery' => false,
                    'selector' => ".tinymce"
                )
            ),
        );
    }

    /**
     * @return array
     */
    public function parameterNoDefProvider()
    {
        return array(
            array(array('tinymce_jquery' => false)),
            array(
                array(
                    'tinymce_jquery' => false,
                    'include_jquery' => false
                )
            ),
            array(
                array(
                    'tinymce_jquery' => false,
                    'include_jquery' => false,
                    'selector' => ".tinymce"
                )
            ),
            array(
                array(
                    'tinymce_jquery' => true,
                    'include_jquery' => false,
                    'selector' => ".tinymce"
                )
            ),
            array(
                array(
                    'tinymce_jquery' => true,
                    'include_jquery' => true,
                    'selector' => ".tinymce"
                )
            ),
        );
    }
}
