<?php

namespace OpenOrchestra\ApiBundle\Transformer;

use OpenOrchestra\ApiBundle\Exceptions\TransformerParameterTypeException;
use OpenOrchestra\ApiBundle\Facade\NewsletterSubscriberFacade;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use OpenOrchestra\ModelInterface\Model\NewsletterSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class NewsletterSubscriberTransformer
 */
class NewsletterSubscriberTransformer extends AbstractTransformer
{
    /**
     * @param NewsletterSubscriberInterface $content
     *
     * @return FacadeInterface
     *
     * @throws TransformerParameterTypeException
     */
    public function transform($subscriber)
    {
        if (!$subscriber instanceof NewsletterSubscriberInterface) {
            throw new TransformerParameterTypeException();
        }

        $facade = new NewsletterSubscriberFacade();

        $facade->id = $subscriber->getId();
        $facade->lastName = $subscriber->getLastName();
        $facade->firstName = $subscriber->getFirstName();
        $facade->email = $subscriber->getEmail();
        $facade->job = $subscriber->getJob();
        $facade->company = $subscriber->getCompany();

        return $facade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'newsletter_subscriber';
    }
}
