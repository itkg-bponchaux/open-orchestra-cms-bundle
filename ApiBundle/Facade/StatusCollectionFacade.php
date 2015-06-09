<?php

namespace OpenOrchestra\ApiBundle\Facade;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class StatusCollectionFacade
 */
class StatusCollectionFacade extends PaginateCollectionFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $collectionName = 'statuses';

    /**
     * @Serializer\Type("array<OpenOrchestra\ApiBundle\Facade\StatusFacade>")
     */
    protected $statuses = array();

    /**
     * @param FacadeInterface $facade
     */
    public function addStatus(FacadeInterface $facade)
    {
        $this->statuses[] = $facade;
    }
}
