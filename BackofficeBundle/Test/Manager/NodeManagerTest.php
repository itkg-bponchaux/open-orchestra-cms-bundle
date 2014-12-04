<?php

namespace PHPOrchestra\BackofficeBundle\Test\Manager;

use PHPOrchestra\BackofficeBundle\Manager\NodeManager;
use PHPOrchestra\ModelBundle\Document\Area;
use PHPOrchestra\ModelBundle\Document\Block;
use PHPOrchestra\ModelBundle\Document\Node;
use PHPOrchestra\ModelBundle\Model\NodeInterface;
use PHPOrchestra\ModelBundle\Repository\NodeRepository;
use Phake;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class NodeManagerTest
 */
class NodeManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NodeManager
     */
    protected $manager;

    protected $nodeRepository;
    protected $siteRepository;
    protected $areaManager;
    protected $blockManager;
    protected $contextManager;
    protected $nodeClass;
    protected $node;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $theme = Phake::mock('PHPOrchestra\ModelBundle\Model\ThemeInterface');
        Phake::when($theme)->getName()->thenReturn('fakeNameTheme');
        $site = Phake::mock('PHPOrchestra\ModelBundle\Model\SiteInterface');
        Phake::when($site)->getTheme()->thenReturn($theme);

        $this->node = Phake::mock('PHPOrchestra\ModelBundle\Document\Node');
        $this->nodeRepository = Phake::mock('PHPOrchestra\ModelBundle\Repository\NodeRepository');
        $this->siteRepository = Phake::mock('PHPOrchestra\ModelBundle\Repository\SiteRepository');
        Phake::when($this->siteRepository)->findOneBySiteId(Phake::anyParameters())->thenReturn($site);
        $this->areaManager = Phake::mock('PHPOrchestra\BackofficeBundle\Manager\AreaManager');
        $this->blockManager = Phake::mock('PHPOrchestra\BackofficeBundle\Manager\BlockManager');
        $this->contextManager = Phake::mock('PHPOrchestra\Backoffice\Context\ContextManager');
        Phake::when($this->contextManager)->getCurrentSiteId()->thenReturn('fakeSiteId');
        Phake::when($this->contextManager)->getCurrentSiteDefaultLanguage()->thenReturn('fakeLanguage');
        $this->nodeClass = 'PHPOrchestra\ModelBundle\Document\Node';

        $this->manager = new NodeManager($this->nodeRepository, $this->siteRepository, $this->areaManager, $this->blockManager, $this->contextManager, $this->nodeClass);
    }

    /**
     * @param NodeInterface   $node
     * @param int             $expectedVersion
     *
     * @dataProvider provideNode
     */
    public function testDuplicateNode(NodeInterface $node, $expectedVersion)
    {
        $alteredNode = $this->manager->duplicateNode($node);

        Phake::verify($alteredNode)->setVersion($expectedVersion);
        Phake::verify($alteredNode)->setStatus(null);
    }

    /**
     * @return array
     */
    public function provideNode()
    {
        $node0 = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($node0)->getVersion()->thenReturn(0);
        Phake::when($node0)->getAreas()->thenReturn(new ArrayCollection());
        Phake::when($node0)->getBlocks()->thenReturn(new ArrayCollection());

        $node1 = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($node1)->getVersion()->thenReturn(1);
        Phake::when($node1)->getAreas()->thenReturn(new ArrayCollection());
        Phake::when($node1)->getBlocks()->thenReturn(new ArrayCollection());

        $node2 = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($node2)->getVersion()->thenReturn(null);
        Phake::when($node2)->getAreas()->thenReturn(new ArrayCollection());
        Phake::when($node2)->getBlocks()->thenReturn(new ArrayCollection());

        return array(
            array($node0, 1),
            array($node1, 2),
            array($node2, 1),
        );
    }

    /**
     * @param NodeInterface   $node
     * @param string          $language
     *
     * @dataProvider provideNodeAndLanguage
     */
    public function testCreateNewLanguageNode(NodeInterface $node, $language)
    {
        $alteredNode = $this->manager->createNewLanguageNode($node, $language);

        Phake::verify($alteredNode)->setVersion(1);
        Phake::verify($alteredNode)->setStatus(null);
        Phake::verify($alteredNode)->setLanguage($language);
    }

    /**
     * @return array
     */
    public function provideNodeAndLanguage()
    {
        $node0 = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($node0)->getVersion()->thenReturn(0);
        Phake::when($node0)->getAreas()->thenReturn(new ArrayCollection());
        Phake::when($node0)->getBlocks()->thenReturn(new ArrayCollection());

        $node1 = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($node1)->getVersion()->thenReturn(1);
        Phake::when($node1)->getAreas()->thenReturn(new ArrayCollection());
        Phake::when($node1)->getBlocks()->thenReturn(new ArrayCollection());

        return array(
            array($node0, 'fr'),
            array($node1, 'en'),
        );
    }

    /**
     * Test deleteTree
     */
    public function testDeleteTree()
    {
        $nodeId = 'nodeId';
        $node = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($node)->getNodeId()->thenReturn($nodeId);
        $nodes = new ArrayCollection();
        $nodes->add($node);
        $nodes->add($node);

        $sonId = 'sonId';
        $son = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($son)->getNodeId()->thenReturn($sonId);
        $sons = new ArrayCollection();
        $sons->add($son);
        $sons->add($son);

        Phake::when($this->nodeRepository)->findByParentIdAndSiteId($nodeId)->thenReturn($sons);
        Phake::when($this->nodeRepository)->findByParentIdAndSiteId($sonId)->thenReturn(new ArrayCollection());

        $this->manager->deleteTree($nodes);

        Phake::verify($node, Phake::times(2))->setDeleted(true);
        Phake::verify($son, Phake::times(2))->setDeleted(true);
        Phake::verify($this->nodeRepository)->findByParentIdAndSiteId($nodeId);
        Phake::verify($this->nodeRepository)->findByParentIdAndSiteId($sonId);
    }

    /**
     * test hydrateNodeFromNodeId
     */
    public function testHydrateNodeFromNodeId()
    {
        $newNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');

        $area = new Area();
        $areas = new ArrayCollection();
        $areas->add($area);
        $block = new Block();
        $blocks = new ArrayCollection();
        $blocks->add($block);
        $oldNodeId = 'oldNodeId';
        $oldNode = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($oldNode)->getAreas()->thenReturn($areas);
        Phake::when($oldNode)->getBlocks()->thenReturn($blocks);
        Phake::when($this->nodeRepository)->findOneByNodeIdAndLanguageAndSiteIdAndLastVersion(Phake::anyParameters())->thenReturn($oldNode);

        $this->manager->hydrateNodeFromNodeId($newNode, $oldNodeId);

        Phake::verify($newNode)->addBlock($block);
        Phake::verify($newNode)->addArea($area);
    }

    /**
     * Test nodeConsistency
     *
     * @param array $nodes
     *
     * @dataProvider generateConsistencyNode
     */
    public function testNodeConsistency($nodes)
    {
        Phake::when($this->areaManager)->areaConsistency(Phake::anyParameters())->thenReturn(true);
        Phake::when($this->blockManager)->blockConsistency(Phake::anyParameters())->thenReturn(true);

        $this->assertTrue($this->manager->nodeConsistency($nodes));
    }

    /**
     * @return array
     */
    public function generateConsistencyNode()
    {
        return array(
            array(array($this->node, $this->node, $this->node)),
            array(array()),
        );
    }

    /**
     * Test initializeNewNode
     */
    public function testInitializeNewNode()
    {
        $node = $this->manager->initializeNewNode();

        $this->assertInstanceOf($this->nodeClass, $node);
        $this->assertEquals('fakeSiteId', $node->getSiteId());
        $this->assertEquals('fakeLanguage', $node->getLanguage());
        $this->assertEquals('fakeNameTheme', $node->getTheme());
    }

    /**
     * @param array $nodes
     *
     * @dataProvider generateNoConsistencyNode
     */
    public function testNodeNoConsistency($nodes)
    {
        $this->assertFalse($this->manager->nodeConsistency($nodes));
    }

    /**
     * @return array
     */
    public function generateNoConsistencyNode()
    {
        return array(
            array(array($this->node, $this->node, $this->node)),
            array($this->node),
        );
    }
}
