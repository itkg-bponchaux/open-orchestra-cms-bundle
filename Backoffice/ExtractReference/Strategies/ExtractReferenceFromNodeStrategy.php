<?php

namespace PHPOrchestra\Backoffice\ExtractReference\Strategies;

use PHPOrchestra\Backoffice\ExtractReference\ExtractReferenceInterface;
use PHPOrchestra\ModelInterface\Model\BlockInterface;
use PHPOrchestra\ModelInterface\Model\NodeInterface;
use PHPOrchestra\ModelInterface\Model\StatusableInterface;

/**
 * Class ExtractReferenceFromNodeStrategy
 */
class ExtractReferenceFromNodeStrategy implements ExtractReferenceInterface
{
    /**
     * @param StatusableInterface $statusableElement
     *
     * @return bool
     */
    public function support(StatusableInterface $statusableElement)
    {
        return $statusableElement instanceof NodeInterface;
    }

    /**
     * @param StatusableInterface|NodeInterface $statusableElement
     *
     * @return array
     */
    public function extractReference(StatusableInterface $statusableElement)
    {
        $references = array();

        /** @var BlockInterface $block */
        foreach ($statusableElement->getBlocks() as $key => $block) {
            foreach ($block->getAttributes() as $attribut) {
                if (strpos($attribut, 'media-') === 0) {
                    $references[substr($attribut, 6)][] = 'node-' . $statusableElement->getId() . '-' . $key;
                }
            }
        }

        return $references;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'node';
    }
}
