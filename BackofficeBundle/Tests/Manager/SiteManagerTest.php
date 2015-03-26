<?php

namespace OpenOrchestra\BackofficeBundle\Tests\Manager;

use OpenOrchestra\BackofficeBundle\Manager\SiteManager;
use OpenOrchestra\ModelInterface\Model\SiteInterface;

/**
 * Class SiteManagerTest
 */
class SiteManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SiteManager
     */
    protected $manager;

    protected $siteClass;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->siteClass = 'OpenOrchestra\ModelBundle\Document\Site';
        $this->manager = new SiteManager($this->siteClass);
    }

    /**
     * Test initializeNewNode
     */
    public function testInitializeNewNode()
    {
        $site = $this->manager->initializeNewSite();

        $this->assertInstanceOf($this->siteClass, $site);
        $this->assertEquals(SiteInterface::PRIORITY_DEFAULT, $site->getSitemapPriority());
        $this->assertEquals(SiteInterface::CHANGE_FREQ_DEFAULT, $site->getSitemapChangefreq());
    }
}
