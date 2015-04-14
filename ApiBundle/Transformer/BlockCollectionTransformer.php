<?php

namespace OpenOrchestra\ApiBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\ApiBundle\Facade\FacadeInterface;
use OpenOrchestra\ApiBundle\Facade\BlockCollectionFacade;

/**
 * Class ContentCollectionTransformer
 */
class BlockCollectionTransformer extends AbstractTransformer
{
    /**
     * @param ArrayCollection $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed, $generateMixed = null, $nodeId = null)
    {
        $facade = new BlockCollectionFacade();

        foreach ($mixed as $block) {
            $facade->addBlock($this->getTransformer('block')->transform($block, false, $nodeId));
        }

        foreach($generateMixed as $block) {
            $facade->addBlock($this->getTransformer('block')->transform($block, true));
        }

        return $facade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'block_collection';
    }
}
