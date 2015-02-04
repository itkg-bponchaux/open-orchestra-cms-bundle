<?php

namespace PHPOrchestra\LogBundle\EventSubscriber;

use PHPOrchestra\ModelInterface\ContentTypeEvents;
use PHPOrchestra\ModelInterface\Event\ContentTypeEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LogContentTypeSubscriber
 */
class LogContentTypeSubscriber implements EventSubscriberInterface
{
    protected $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ContentTypeEvent $event
     */
    public function contentTypeCreation(ContentTypeEvent $event)
    {
        $contentType = $event->getContentType();
        $this->logger->info('Create a new content', array(
            'content_contentId' => $contentType->getContentTypeId(),
            'content_name' => $contentType->getName()
        ));
    }

    /**
     * @param ContentTypeEvent $event
     */
    public function contentTypeDelete(ContentTypeEvent $event)
    {
        $contentType = $event->getContentType();
        $this->logger->info('Delete a content', array(
            'content_contentId' => $contentType->getContentTypeId(),
            'content_name' => $contentType->getName()
        ));
    }

    /**
     * @param ContentTypeEvent $event
     */
    public function contentTypeUpdate(ContentTypeEvent $event)
    {
        $contentType = $event->getContentType();
        $this->logger->info('Update a content', array(
            'content_contentId' => $contentType->getContentTypeId(),
            'content_name' => $contentType->getName()
        ));
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ContentTypeEvents::CONTENT_TYPE_CREATE => 'contentTypeEvent',
            ContentTypeEvents::CONTENT_TYPE_DELETE => 'contentTypeEvent',
            ContentTypeEvents::CONTENT_TYPE_UPDATE => 'contentTypeEvent',
        );
    }
}
