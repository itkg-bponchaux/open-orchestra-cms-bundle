<?php

namespace PHPOrchestra\Backoffice\GenerateForm;

use PHPOrchestra\ModelBundle\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Interface GenerateFormInterface
 */
interface GenerateFormInterface
{
    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    public function support(BlockInterface $block);

    /**
     * @param FormInterface  $form
     * @param BlockInterface $block
     */
    public function buildForm(FormInterface $form, BlockInterface $block);

    /**
     * @return string
     */
    public function getName();
}
