<?php

namespace PHPOrchestra\ApiBundle\Transformer;

use PHPOrchestra\ApiBundle\Facade\ContentAttributeFacade;
use PHPOrchestra\ApiBundle\Facade\FacadeInterface;
use PHPOrchestra\ModelBundle\Model\ContentAttributeInterface;

/**
 * Class ContentAttributeTransformer
 */
class ContentAttributeTransformer extends AbstractTransformer
{
    /**
     * @param ContentAttributeInterface $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $facade = new ContentAttributeFacade();

        $facade->id = $mixed->getId();
        $facade->name = $mixed->getName();
        $facade->value = $mixed->getValue();

        return $facade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'content_attribute';
    }
}
