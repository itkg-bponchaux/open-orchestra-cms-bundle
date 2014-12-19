<?php

namespace PHPOrchestra\BackofficeBundle\Test\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use PHPOrchestra\BackofficeBundle\Form\DataTransformer\EmbedKeywordsToKeywordsTransformer;
use PHPOrchestra\ModelInterface\Model\EmbedKeywordInterface;

/**
 * Class EmbedKeywordsToKeywordsTransformerTest
 */
class EmbedKeywordsToKeywordsTransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EmbedKeywordsToKeywordsTransformer
     */
    protected $transformer;

    protected $keywordClass;
    protected $documentManager;
    protected $keywordRepository;
    protected $embedKeywordClass;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->embedKeywordClass = 'PHPOrchestra\ModelBundle\Document\EmbedKeyword';
        $this->keywordClass = 'PHPOrchestra\ModelBundle\Document\Keyword';

        $this->documentManager = Phake::mock('Doctrine\ODM\MongoDB\DocumentManager');
        $this->keywordRepository = Phake::mock('PHPOrchestra\ModelInterface\Repository\KeywordRepositoryInterface');
        Phake::when($this->keywordRepository)->getDocumentManager()->thenReturn($this->documentManager);

        $this->transformer = new EmbedKeywordsToKeywordsTransformer($this->keywordRepository, $this->embedKeywordClass, $this->keywordClass);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\Form\DataTransformerInterface', $this->transformer);
    }

    /**
     * Test with null data
     */
    public function testTransformWithNullData()
    {
        $this->assertSame('', $this->transformer->transform(null));
    }

    /**
     * @param string $tagLabel
     *
     * @dataProvider provideTagLabel
     */
    public function testTransformWithTag($tagLabel)
    {
        $keyword = Phake::mock('PHPOrchestra\ModelInterface\Model\KeywordInterface');
        Phake::when($keyword)->getLabel()->thenReturn($tagLabel);
        $keywords = new ArrayCollection();
        $keywords->add($keyword);
        $keywords->add($keyword);

        $this->assertSame($tagLabel . ',' . $tagLabel, $this->transformer->transform($keywords));
    }

    /**
     * @return array
     */
    public function provideTagLabel()
    {
        return array(
            array('tag'),
            array('label'),
        );
    }

    /**
     * Test with no data
     */
    public function testReverseTransformWithNullData()
    {
        $embedKeywords = $this->transformer->reverseTransform('');

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $embedKeywords);
        $this->assertCount(0, $embedKeywords);
    }

    /**
     * @param string $tagLabel
     *
     * @dataProvider provideTagLabel
     */
    public function testReverseTransformWithExistingTag($tagLabel)
    {
        $keyword = Phake::mock('PHPOrchestra\ModelInterface\Model\KeywordInterface');
        Phake::when($keyword)->getLabel()->thenReturn($tagLabel);
        Phake::when($this->keywordRepository)->findOneByLabel(Phake::anyParameters())->thenReturn($keyword);

        $embedKeywords = $this->transformer->reverseTransform($tagLabel . ',' . $tagLabel);

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $embedKeywords);
        $this->assertCount(2, $embedKeywords);
        $this->assertSameKeyword($tagLabel, $embedKeywords->get(0));
        $this->assertSameKeyword($tagLabel, $embedKeywords->get(1));
    }

    /**
     * @param string $tagLabel
     *
     * @dataProvider provideTagLabel
     */
    public function testReverseTransformWithNonExistingTag($tagLabel)
    {
        Phake::when($this->keywordRepository)->findOneByLabel(Phake::anyParameters())->thenReturn(null);

        $embedKeywords = $this->transformer->reverseTransform($tagLabel . ',' . $tagLabel);

        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $embedKeywords);
        $this->assertCount(2, $embedKeywords);
        $this->assertSameKeyword($tagLabel, $embedKeywords->get(0));
        $this->assertSameKeyword($tagLabel, $embedKeywords->get(1));
        Phake::verify($this->documentManager, Phake::times(2))->persist(Phake::anyParameters());
        Phake::verify($this->documentManager, Phake::times(2))->flush(Phake::anyParameters());
        Phake::verify($this->documentManager, Phake::never())->flush();
    }

    /**
     * @param string                $tagLabel
     * @param EmbedKeywordInterface $embedKeyword
     */
    protected function assertSameKeyword($tagLabel, $embedKeyword)
    {
        $this->assertSame($tagLabel, $embedKeyword->getLabel());
        $this->assertInstanceOf('PHPOrchestra\ModelInterface\Model\EmbedKeywordInterface', $embedKeyword);
    }
}
