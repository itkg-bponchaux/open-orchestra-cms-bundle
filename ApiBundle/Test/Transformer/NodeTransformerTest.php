<?php

namespace PHPOrchestra\ApiBundle\Test\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PHPOrchestra\ApiBundle\Transformer\NodeTransformer;


/**
 * Class NodeTransformerTest
 */
class NodeTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NodeTransformer
     */
    protected $nodeTransformer;

    protected $transformerManager;
    protected $encryptionManager;
    protected $statusRepository;
    protected $eventDispatcher;
    protected $siteRepository;
    protected $transformer;
    protected $statusId;
    protected $router;
    protected $status;
    protected $node;
    protected $site;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->eventDispatcher = Phake::mock('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $this->node = Phake::mock('PHPOrchestra\ModelInterface\Model\NodeInterface');
        $siteAlias = Phake::mock('PHPOrchestra\ModelInterface\Model\SiteAliasInterface');
        $siteAliases = new ArrayCollection();
        $siteAliases->add($siteAlias);
        $this->site = Phake::mock('PHPOrchestra\ModelInterface\Model\SiteInterface');
        Phake::when($this->site)->getAliases()->thenReturn($siteAliases);
        $this->status = Phake::mock('PHPOrchestra\ModelInterface\Model\StatusInterface');
        $this->statusId = 'StatusId';
        Phake::when($this->status)->getId(Phake::anyParameters())->thenReturn($this->statusId);

        $this->encryptionManager = Phake::mock('PHPOrchestra\BaseBundle\Manager\EncryptionManager');

        $this->siteRepository = Phake::mock('PHPOrchestra\ModelInterface\Repository\SiteRepositoryInterface');
        Phake::when($this->siteRepository)->findOneBySiteId(Phake::anyParameters())->thenReturn($this->site);

        $this->statusRepository = Phake::mock('PHPOrchestra\ModelInterface\Repository\StatusRepositoryInterface');
        Phake::when($this->statusRepository)->find(Phake::anyParameters())->thenReturn($this->status);

        $this->transformer = Phake::mock('PHPOrchestra\ApiBundle\Transformer\BlockTransformer');
        $this->router = Phake::mock('Symfony\Component\Routing\RouterInterface');
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn('route');

        $this->transformerManager = Phake::mock('PHPOrchestra\ApiBundle\Transformer\TransformerManager');
        Phake::when($this->transformerManager)->get(Phake::anyParameters())->thenReturn($this->transformer);
        Phake::when($this->transformerManager)->getRouter()->thenReturn($this->router);

        $this->nodeTransformer = new NodeTransformer($this->encryptionManager, $this->siteRepository, $this->statusRepository, $this->eventDispatcher);

        $this->nodeTransformer->setContext($this->transformerManager);
    }

    /**
     * Test transform
     */
    public function testTransform()
    {
        $facade = Phake::mock('PHPOrchestra\ApiBundle\Facade\FacadeInterface');

        Phake::when($this->transformer)->transform(Phake::anyParameters())->thenReturn($facade);
        $area = Phake::mock('PHPOrchestra\ModelInterface\Model\AreaInterface');
        $areas = new ArrayCollection();
        $areas->add($area);

        Phake::when($this->node)->getAreas()->thenReturn($areas);

        $facade = $this->nodeTransformer->transform($this->node);

        $this->assertInstanceOf('PHPOrchestra\ApiBundle\Facade\NodeFacade', $facade);
        $this->assertArrayHasKey('_self_form', $facade->getLinks());
        $this->assertArrayHasKey('_self_duplicate', $facade->getLinks());
        $this->assertArrayHasKey('_self_version', $facade->getLinks());
        $this->assertArrayHasKey('_self_preview', $facade->getLinks());
        $this->assertArrayHasKey('_language_list', $facade->getLinks());
        $this->assertArrayHasKey('_self_status_change', $facade->getLinks());
        $this->assertArrayHasKey('_existing_block', $facade->getLinks());
        Phake::verify($this->router, Phake::times(10))->generate(Phake::anyParameters());
        Phake::verify($this->transformer)->transform($area, $this->node);
        Phake::verify($this->siteRepository)->findOneBySiteId(Phake::anyParameters());
    }

    /**
     * Test transformVersion
     */
    public function testTransformVersion()
    {
        $facade = $this->nodeTransformer->transformVersion($this->node);

        $this->assertInstanceOf('PHPOrchestra\ApiBundle\Facade\NodeFacade', $facade);
        $this->assertArrayHasKey('_self', $facade->getLinks());
        Phake::verify($this->router)->generate(Phake::anyParameters());
    }

    /**
     * @param mixed $facade
     * @param mixed $source
     * @param int   $searchCount
     * @param int   $setCount
     *
     * @dataProvider getChangeStatus
     */
    public function testReverseTransform($facade, $source, $searchCount, $setCount)
    {
        $this->nodeTransformer->reverseTransform($facade, $source);

        Phake::verify($this->statusRepository, Phake::times($searchCount))->find(Phake::anyParameters());
        Phake::verify($this->eventDispatcher, Phake::times($setCount))->dispatch(Phake::anyParameters());

        if ($source) {
            Phake::verify($source, Phake::times($setCount))->setStatus(Phake::anyParameters());
        }
    }

    /**
     * @return array
     */
    public function getChangeStatus()
    {
        $facadeA = Phake::mock('PHPOrchestra\ApiBundle\Facade\NodeFacade');

        $facadeB = Phake::mock('PHPOrchestra\ApiBundle\Facade\NodeFacade');
        $facadeB->statusId = 'fakeId';

        $node = Phake::mock('PHPOrchestra\ModelInterface\Model\NodeInterface');

        return array(
            array($facadeA, null, 0, 0),
            array($facadeA, $node, 0, 0),
            array($facadeB, $node, 1, 1)
        );
    }
}
