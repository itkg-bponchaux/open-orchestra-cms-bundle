<?php

namespace OpenOrchestra\BackofficeBundle\Tests\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Phake;
use OpenOrchestra\BackofficeBundle\Form\Type\OrchestraKeywordsType;

/**
 * Class OrchestraKeywordsTypeTest
 */
class OrchestraKeywordsTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OrchestraKeywordsType
     */
    protected $form;

    protected $router;
    protected $builder;
    protected $keyword1;
    protected $keyword2;
    protected $keywords;
    protected $transformer;
    protected $keywordRepository;

    /**
     * Set up the text
     */
    public function setUp()
    {
        $this->keyword1 = Phake::mock('OpenOrchestra\ModelInterface\Model\KeywordInterface');
        $this->keyword2 = Phake::mock('OpenOrchestra\ModelInterface\Model\KeywordInterface');
        $this->keywords = new ArrayCollection();
        $this->keywords->add($this->keyword1);
        $this->keywords->add($this->keyword2);
        $this->keywordRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\KeywordRepositoryInterface');
        Phake::when($this->keywordRepository)->findAll()->thenReturn($this->keywords);

        $this->builder = Phake::mock('Symfony\Component\Form\FormBuilder');
        $this->transformer = Phake::mock('OpenOrchestra\BackofficeBundle\Form\DataTransformer\EmbedKeywordsToKeywordsTransformer');

        $this->router = Phake::mock('Symfony\Component\Routing\RouterInterface');

        $this->form = new OrchestraKeywordsType($this->transformer, $this->keywordRepository, $this->router);
    }

    /**
     * Test Name
     */
    public function testName()
    {
        $this->assertSame('orchestra_keywords', $this->form->getName());
    }

    /**
     * Test Parent
     */
    public function testParent()
    {
        $this->assertSame('text', $this->form->getParent());
    }

    /**
     * @param string $tagLabel
     *
     * @dataProvider provideTagLabel
     */
    public function testConfigureOptions($tagLabel)
    {
        $route = 'path';
        Phake::when($this->router)->generate(Phake::anyParameters())->thenReturn($route);
        Phake::when($this->keyword1)->getLabel()->thenReturn($tagLabel);
        Phake::when($this->keyword2)->getLabel()->thenReturn($tagLabel);

        $resolverMock = Phake::mock('Symfony\Component\OptionsResolver\OptionsResolver');

        $this->form->configureOptions($resolverMock);

        Phake::verify($resolverMock)->setDefaults(array(
            'embedded' => true,
            'attr' => array(
                'class' => 'select2',
                'data-tags' => json_encode(array($tagLabel, $tagLabel)),
                'data-check' => $route
        )));
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
     * Test model transformer
     */
    public function testBuildForm()
    {
        $this->form->buildForm($this->builder, array('embedded' => true));

        Phake::verify($this->builder)->addModelTransformer($this->transformer);
    }
}
