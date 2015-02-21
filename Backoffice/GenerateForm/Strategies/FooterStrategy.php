<?php

namespace OpenOrchestra\Backoffice\GenerateForm\Strategies;

use OpenOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use OpenOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FooterStrategy
 */
class FooterStrategy extends AbstractBlockStrategy
{
    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::FOOTER === $block->getComponent();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'footer';
    }

}
