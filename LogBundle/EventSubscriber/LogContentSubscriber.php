<?php

namespace PHPOrchestra\LogBundle\EventSubscriber;

use PHPOrchestra\ModelInterface\ContentEvents;
use PHPOrchestra\ModelInterface\Event\ContentEvent;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LogContentSubscriber
 */
class LogContentSubscriber implements EventSubscriberInterface
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
     * @param ContentEvent $event
     */
    public function contentCreation(ContentEvent $event)
    {
        $content = $event->getContent();
        $this->logger->info('Create a new content', array('content_contentId' => $content->getContentId(), 'content_name' => $content->getName()));
    }

    /**
     * @param ContentEvent $event
     */
    public function contentDelete(ContentEvent $event)
    {
        $content = $event->getContent();
        $this->logger->info('Delete a content', array('content_contentId' => $content->getContentId(), 'content_name' => $content->getName()));
    }

    /**
     * @param ContentEvent $event
     */
    public function contentUpdate(ContentEvent $event)
    {
        $content = $event->getContent();
        $this->logger->info('Update a content', array('content_contentId' => $content->getContentId(), 'content_name' => $content->getName()));
    }

    /**
     * @param ContentEvent $event
     */
    public function contentDuplicate(ContentEvent $event)
    {
        $content = $event->getContent();
        $this->logger->info('Duplicate a content', array(
            'content_contentId' => $content->getContentId(),
            'content_name' => $content->getName(),
            'content_version' => $content->getVersion()
        ));
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ContentEvents::CONTENT_CREATION => 'contentEvent',
            ContentEvents::CONTENT_DELETE => 'contentEvent',
            ContentEvents::CONTENT_UPDATE => 'contentEvent',
            ContentEvents::CONTENT_DUPLICATE => 'contentEvent',
        );
    }
}
