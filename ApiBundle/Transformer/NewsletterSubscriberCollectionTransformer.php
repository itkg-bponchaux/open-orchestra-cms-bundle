<?php

namespace OpenOrchestra\ApiBundle\Transformer;

use Doctrine\Common\Collections\Collection;
use OpenOrchestra\ApiBundle\Facade\NewsletterSubscriberCollectionFacade;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;

/**
 * Class NewsletterSubscriberCollectionTransformer
 */
class NewsletterSubscriberCollectionTransformer extends AbstractTransformer
{
    /**
     * @param Collection  $contentCollection
     *
     * @return FacadeInterface
     */
    public function transform($newsletterSubscriberCollection)
    {
        $facade = new NewsletterSubscriberCollectionFacade();

        foreach ($newsletterSubscriberCollection as $subscriber) {
            $facade->addSubscriber($this->getTransformer('newsletter_subscriber')->transform($subscriber));
        }

        $facade->addLink('_self', $this->generateRoute(
            'open_orchestra_api_newsletter_subscribers_list',
            array()
        ));

        return $facade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'newsletter_subscriber_collection';
    }
}
