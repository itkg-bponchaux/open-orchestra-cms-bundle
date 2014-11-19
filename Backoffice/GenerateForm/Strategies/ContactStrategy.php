<?php

namespace PHPOrchestra\Backoffice\GenerateForm\Strategies;

use PHPOrchestra\Backoffice\GenerateForm\Strategies\AbstractBlockStrategy;
use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class ContactStrategy
 */
class ContactStrategy extends AbstractBlockStrategy
{
    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::CONTACT === $block->getComponent();
    }

    /**
     * @param FormInterface  $form
     * @param BlockInterface $block
     */
    public function buildForm(FormInterface $form, BlockInterface $block)
    {
        $attributes = $block->getAttributes();

        $form->add('id', 'text', array(
            'mapped' => false,
            'data' => array_key_exists('id', $attributes)? $attributes['id']:'',
            'required' => false,
        ));
        $form->add('class', 'text', array(
            'mapped' => false,
            'data' => array_key_exists('class', $attributes)? $attributes['class']:'',
            'required' => false,
        ));
        $form->add('form', 'text', array(
            'mapped' => false,
            'data' => array_key_exists('form', $attributes)? $attributes['form']:'',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contact';
    }

}
