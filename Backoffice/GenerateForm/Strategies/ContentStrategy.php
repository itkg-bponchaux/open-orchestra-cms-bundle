<?php

namespace PHPOrchestra\Backoffice\GenerateForm\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class ContentStrategy
 */
class ContentStrategy extends AbstractBlockStrategy
{
    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::CONTENT === $block->getComponent();
    }

    /**
     * @param FormInterface  $form
     * @param BlockInterface $block
     */
    public function buildForm(FormInterface $form, BlockInterface $block)
    {
        $attributes = $block->getAttributes();

        $form->add('class', 'textarea', array(
            'mapped' => false,
            'data' => array_key_exists('class', $attributes)? $attributes['class']:'',
            'required' => false,
        ));
        $form->add('id', 'text', array(
            'mapped' => false,
            'data' => array_key_exists('id', $attributes)? $attributes['id']:'',
            'required' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'content';
    }
}
