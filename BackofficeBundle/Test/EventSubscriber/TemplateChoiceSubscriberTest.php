<?php

namespace PHPOrchestra\BackofficeBundle\Test\EventSubscriber;

use Phake;
use PHPOrchestra\BackofficeBundle\EventSubscriber\TemplateChoiceSubscriber;
use PHPOrchestra\BackofficeBundle\EventSubscriber\BlockTypeSubscriber;
use PHPOrchestra\ModelBundle\Model\TemplateInterface;
use Symfony\Component\Form\FormEvents;
use PHPOrchestra\ModelBundle\Document\Node;

/**
 * Class TemplateChoiceSubscriberTest
 */
class TemplateChoiceSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateChoiceSubscriber
     */
    protected $subscriber;

    protected $event;
    protected $form;
    protected $templateRepository;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->form = Phake::mock('Symfony\Component\Form\FormBuilder');
        Phake::when($this->form)->add(Phake::anyParameters())->thenReturn($this->form);

        $this->event = Phake::mock('Symfony\Component\Form\FormEvent');
        Phake::when($this->event)->getForm()->thenReturn($this->form);

        $this->templateRepository = Phake::mock('PHPOrchestra\ModelBundle\Repository\TemplateRepository');

        $this->subscriber = new TemplateChoiceSubscriber($this->templateRepository);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->subscriber);
    }

    /**
     * Test subscribed events
     */
    public function testEventSubscribed()
    {
        $this->assertArrayHasKey(FormEvents::PRE_SUBMIT, $this->subscriber->getSubscribedEvents());
        $this->assertArrayHasKey(FormEvents::PRE_SET_DATA, $this->subscriber->getSubscribedEvents());
    }

    /**
     * @param array             $data
     * @param TemplateInterface $template
     *
     * @dataProvider getDataTemplate
     */
    public function testPreSubmit($data, $template)
    {
        $templateChoiceContainer = Phake::mock('PHPOrchestra\ModelBundle\Document\Node');

        Phake::when($templateChoiceContainer)->getData()->thenReturn($data);
        Phake::when($this->form)->getData()->thenReturn($templateChoiceContainer);

        Phake::when($this->event)->getData()->thenReturn($data);
        Phake::when($this->templateRepository)->findOneByTemplateId(Phake::anyParameters())->thenReturn($template);

        $this->subscriber->preSubmit($this->event);

        Phake::verify($templateChoiceContainer, Phake::times((null === $template)? 0: 1))->setAreas((null !== $template)? $template->getAreas() : '');
        Phake::verify($templateChoiceContainer, Phake::times((null === $template)? 0: 1))->setBlocks((null !== $template)? $template->getBlocks() : '');
    }

    /**
     * Templates provider
     *
     * @return array
     */
    public function getDataTemplate()
    {
        $areas = Phake::mock('Doctrine\Common\Collections\Collection');
        $blocks = Phake::mock('Doctrine\Common\Collections\Collection');

        $template = Phake::mock('PHPOrchestra\ModelBundle\Model\TemplateInterface');
        Phake::when($template)->getAreas()->thenReturn($areas);
        Phake::when($template)->getBlocks()->thenReturn($blocks);

        return array(
            array(array('templateId' => 1), $template),
            array(array('templateId' => 1), null),
        );
    }

    /**
     * @param array             $data
     * @param TemplateInterface $template
     *
     * @dataProvider getDataTemplate
     */
    public function testPreSubmitWithExistingNode($data, $template)
    {
        $templateChoiceContainer = Phake::mock('PHPOrchestra\ModelBundle\Document\Node');

        Phake::when($templateChoiceContainer)->getData()->thenReturn($data);
        Phake::when($templateChoiceContainer)->getId()->thenReturn('nodeId');
        Phake::when($this->form)->getData()->thenReturn($templateChoiceContainer);

        Phake::when($this->event)->getData()->thenReturn($data);
        Phake::when($this->templateRepository)->findOneByTemplateId(Phake::anyParameters())->thenReturn($template);

        $this->subscriber->preSubmit($this->event);

        Phake::verify($templateChoiceContainer, Phake::never())->setAreas(Phake::anyParameters());
        Phake::verify($templateChoiceContainer, Phake::never())->setBlocks(Phake::anyParameters());
    }

    /**
     * test the build form
     *
     * @param array $templates
     * @param array $expectedResult
     *
     * @dataProvider getTemplate
     */
    public function testPreSetDataOnNewNode($templates, $expectedResult)
    {
        $node = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($this->event)->getData()->thenReturn($node);
        Phake::when($this->templateRepository)->findByDeleted(Phake::anyParameters())->thenReturn($templates);

        $this->subscriber->preSetData($this->event);

        Phake::verify($this->form)->add('templateId', 'choice', array(
            'choices' => $expectedResult,
            'label' => 'php_orchestra_backoffice.form.node.template_id'
        ));
    }

    /**
     * Templates provider
     *
     * @return array
     */
    public function getTemplate()
    {
        $id0 = 'fakeId0';
        $name0 = 'fakeName0';
        $id1 = 'fakeId1';
        $name1 = 'fakeName1';

        $template0 = Phake::mock('PHPOrchestra\ModelBundle\Model\TemplateInterface');
        Phake::when($template0)->getTemplateId()->thenReturn($id0);
        Phake::when($template0)->getName()->thenReturn($name0);

        $template1 = Phake::mock('PHPOrchestra\ModelBundle\Model\TemplateInterface');
        Phake::when($template1)->getTemplateId()->thenReturn($id1);
        Phake::when($template1)->getName()->thenReturn($name1);

        return array(
            array(
                array($template0, $template1), array($id0 => $name0, $id1 => $name1)
            )
        );
    }

    /**
     * test the build form
     *
     * @param array $templates
     * @param array $expectedResult
     *
     * @dataProvider getTemplate
     */
    public function testPreSetDataWithExistingNode($templates, $expectedResult)
    {
        $node = Phake::mock('PHPOrchestra\ModelBundle\Model\NodeInterface');
        Phake::when($node)->getId()->thenReturn('fakeId');
        Phake::when($this->event)->getData()->thenReturn($node);
        Phake::when($this->templateRepository)->findByDeleted(Phake::anyParameters())->thenReturn($templates);

        $this->subscriber->preSetData($this->event);

        Phake::verify($this->form, Phake::never())->add(Phake::anyParameters());
    }
}
