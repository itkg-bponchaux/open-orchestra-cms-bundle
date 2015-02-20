<?php

namespace PHPOrchestra\BackofficeBundle\StrategyManager;

use PHPOrchestra\Backoffice\GenerateForm\GenerateFormInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use Symfony\Component\Form\FormBuilderInterface;

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
     * @param FormBuilderInterface  $form
     * @param array                 $options
     * @param BlockInterface        $block
     */
    public function buildForm(FormBuilderInterface $form, array $options, BlockInterface $block)
    {
        /** @var GenerateFormInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
                $strategy->buildForm($form, $options);
            }
        }
    }

    /**
     * @param BlockInterface $block
     *
     * @return GenerateFormInterface
     */
    public function createForm(BlockInterface $block)
    {
        /** @var GenerateFormInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
                return $strategy;
            }
        }
    }

    /**
     * @param BlockInterface $block
     *
     * @return string
     */
    public function getTemplate(BlockInterface $block)
    {
        /** @var GenerateFormInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->support($block)) {
                return $strategy->getTemplate();
            }
        }
    }
}
