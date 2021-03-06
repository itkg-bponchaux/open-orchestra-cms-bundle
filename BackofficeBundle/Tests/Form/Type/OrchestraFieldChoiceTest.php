<?php

namespace OpenOrchestra\BackofficeBundle\Tests\Form\Type;

use OpenOrchestra\BackofficeBundle\Form\Type\OrchestraFieldChoice;
use Phake;

/**
 * Class OrchestraFieldChoiceTest
 */
class OrchestraFieldChoiceTest extends \PHPUnit_Framework_TestCase
{
    protected $form;
    protected $transformerArrayToString;
    protected $transformerStringToArray;
    protected $builder;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->builder = Phake::mock('Symfony\Component\Form\FormBuilder');
        $this->transformerArrayToString = Phake::mock('OpenOrchestra\BackofficeBundle\Form\DataTransformer\ChoiceArrayToStringTransformer');
        $this->transformerStringToArray = Phake::mock('OpenOrchestra\BackofficeBundle\Form\DataTransformer\ChoiceStringToArrayTransformer');
        $this->form = new OrchestraFieldChoice($this->transformerArrayToString, $this->transformerStringToArray);
    }

    /**
     * @param boolean $multiple
     * @dataProvider providerOptionMultiple
     */
    public function testBuildForm($multiple)
    {
        $this->form->buildForm($this->builder, array('multiple' => $multiple));

        if ($multiple) {
            Phake::verify($this->builder)->addModelTransformer($this->transformerStringToArray);
        } else {
            Phake::verify($this->builder)->addModelTransformer($this->transformerArrayToString);
        }
    }

    /**
     * @return array
     */
    public function providerOptionMultiple()
    {
        return array(
            array(true),
            array(false),
        );
    }

    /**
     * Test parent
     */
    public function testGetParent()
    {
        $this->assertEquals('choice', $this->form->getParent());
    }

    /**
     * test Name
     */
    public function testGetName()
    {
        $this->assertEquals('orchestra_field_choice', $this->form->getName());
    }
}
