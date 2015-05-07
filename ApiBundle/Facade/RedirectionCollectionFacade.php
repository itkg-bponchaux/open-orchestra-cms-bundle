<?php

namespace OpenOrchestra\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\AbstractFacade;

/**
 * Class RedirectionCollection
 */
class RedirectionCollectionFacade extends AbstractFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $collectionName = 'redirections';

    /**
     * @Serializer\Type("array<OpenOrchestra\ApiBundle\Facade\RedirectionFacade>")
     */
    protected $redirections = array();

    /**
     * @param FacadeInterface $facade
     */
    public function addRedirection(FacadeInterface $facade)
    {
        $this->redirections[] = $facade;
    }
}
