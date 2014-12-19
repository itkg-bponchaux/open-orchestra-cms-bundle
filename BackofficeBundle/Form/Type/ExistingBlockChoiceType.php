<?php

namespace PHPOrchestra\BackofficeBundle\Form\Type;

use PHPOrchestra\ModelInterface\Repository\NodeRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ExistingBlockChoiceType
 */
class ExistingBlockChoiceType extends AbstractType
{
    protected $nodeRepository;

    /**
     * @param NodeRepositoryInterface $nodeRepository
     */
    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $nodes = $this->nodeRepository->findAll();
        if (!empty($nodes)) {
            $choices = array();
            foreach ($nodes as $node) {
                foreach ($node->getBlocks() as $blockIndex => $block) {
                    $attributes = $block->getAttributes();
                    if (!empty($attributes) && array_key_exists('title', $attributes)) {
                        $label = $attributes['title'];
                    } else {
                        $label = $block->getComponent();
                    }
                    $choices[$node->getNodeId()][$node->getNodeId() . ':' . $blockIndex] = $label;
                }
            }

            $builder->add('existingBlock', 'choice', array(
                'required' => false,
                'choices' => $choices,
                'label' => 'php_orchestra_backoffice.form.area.existing_block'
            ));
        }
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'existing_block';
    }
}
