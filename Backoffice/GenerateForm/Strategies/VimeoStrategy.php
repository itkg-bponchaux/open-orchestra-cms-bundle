<?php

namespace PHPOrchestra\Backoffice\GenerateForm\Strategies;

use PHPOrchestra\Backoffice\GenerateForm\Strategies\AbstractBlockStrategy;
use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class VimeoStrategy
 */
class VimeoStrategy extends AbstractBlockStrategy
{
    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::VIMEO === $block->getComponent();
    }

    /**
     * @param FormInterface  $form
     * @param BlockInterface $block
     */
    public function buildForm(FormInterface $form, BlockInterface $block)
    {
        $attributes = $block->getAttributes();

        $empty = array(
            'videoId' => '',
        );

        $attributes = array_merge($empty, $attributes);

        $form->add('videoId', 'orchestra_video', array(
            'mapped' => false,
            'data' => $attributes['videoId'],
            'label' => 'php_orchestra_backoffice.block.vimeo.video_id',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'vimeo';
    }
}
