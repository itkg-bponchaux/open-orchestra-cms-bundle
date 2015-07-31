<?php

namespace OpenOrchestra\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

/**
 * Class NewsletterSubscriberCollectionFacade
 */
class NewsletterSubscriberCollectionFacade extends PaginateCollectionFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $collectionName = 'subscribers';

    /**
     * @Serializer\Type("array<OpenOrchestra\ApiBundle\Facade\NewsletterSubscriberFacade>")
     */
    protected $subscribers = array();

    /**
     * @param FacadeInterface $facade
     */
    public function addSubscriber(FacadeInterface $facade)
    {
        $this->subscribers[] = $facade;
    }
}
