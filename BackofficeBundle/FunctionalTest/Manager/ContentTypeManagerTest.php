<?php

namespace PHPOrchestra\BackofficeBundle\FunctionalTest\Manager;

use PHPOrchestra\BackofficeBundle\Manager\ContentTypeManager;
use PHPOrchestra\ModelInterface\Model\ContentTypeInterface;
use PHPOrchestra\ModelBundle\Repository\ContentTypeRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class ContentTypeManagerTest
 */
class ContentTypeManagerTest extends KernelTestCase
{
    /**
     * @var ContentTypeManager
     */
    protected $manager;

    /**
     * @var ContentTypeRepository
     */
    protected $contentTypeRepository;

    /**
     * Set up the test
     */
    public function setUp()
    {
        static::bootKernel();
        $this->contentTypeRepository = static::$kernel->getContainer()->get('php_orchestra_model.repository.content_type');

        $this->manager = new ContentTypeManager();
    }

    /**
     * @param string $contentTypeId
     *
     * @dataProvider provideContentTypeId
     */
    public function testDuplicate($contentTypeId)
    {
        /** @var ContentTypeInterface $contentType */
        $contentType = $this->contentTypeRepository->findOneByContentTypeId($contentTypeId);

        /** @var ContentTypeInterface $newContentType */
        $newContentType = $this->manager->duplicate($contentType);

        $this->assertNull($newContentType->getId());
        $this->assertEquals($contentType->getVersion() + 1, $newContentType->getVersion());
        $this->assertCount($contentType->getNames()->count(), $newContentType->getNames());
        $this->assertSame($contentType->getName('fr'), $newContentType->getName('fr'));
        $this->assertSame($contentType->getName('en'), $newContentType->getName('en'));
        $this->assertCount($contentType->getFields()->count(), $newContentType->getFields());
        $this->assertCount($contentType->getFields()->first()->getLabels()->count(), $newContentType->getFields()->first()->getLabels());
        $this->assertSame($contentType->getFields()->first()->getLabel('fr'), $newContentType->getFields()->first()->getLabel('fr'));
    }

    /**
     * @return array
     */
    public function provideContentTypeId()
    {
        return array(
            array('news'),
            array('car'),
            array('customer'),
        );
    }
}
