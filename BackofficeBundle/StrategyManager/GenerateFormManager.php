<?php

namespace PHPOrchestra\BackofficeBundle\StrategyManager;
use PHPOrchestra\Backoffice\GenerateForm\GenerateFormInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;


/**
 * Class GenerateFormManager
 */
class GenerateFormManager
{
    protected $strategies = array();

    /**
     * @param GenerateFormInterface $strategy
     */
    public function addStrategy(GenerateFormInterface $strategy)
    {
        $this->strategies[$strategy->getName()] = $strategy;
    }

    /**
     * @param FormInterface  $form
     * @param BlockInterface $block
     */
    public function buildForm(FormInterface $form, BlockInterface $block)
    {
        /** @var GenerateFormInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
                $strategy->buildForm($form, $block);
            }
        }
    }

    /**
     * @param FormInterface  $form
     * @param BlockInterface $block
     */
    public function alterFormAfterSubmit(FormInterface $form, BlockInterface $block)
    {
        /** @var GenerateFormInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
                $strategy->alterFormAfterSubmit($form, $block);
            }
        }
    }
}
