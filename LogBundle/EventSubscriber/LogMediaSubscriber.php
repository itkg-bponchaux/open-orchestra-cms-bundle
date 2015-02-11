<?php

namespace PHPOrchestra\LogBundle\EventSubscriber;

use PHPOrchestra\Media\Event\MediaEvent;
use PHPOrchestra\Media\MediaEvents;
use PHPOrchestra\Media\Event\FolderEvent;
use PHPOrchestra\Media\FolderEvents;
use PHPOrchestra\Media\Model\FolderInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LogMediaSubscriber
 */
class LogMediaSubscriber implements EventSubscriberInterface
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
     * @param MediaEvent $event
     */
    public function mediaAddImage(MediaEvent $event)
    {
        $this->mediaInfo('php_orchestra_log.media.add_image', $event->getMedia()->getName());
    }

    /**
     * @param MediaEvent $event
     */
    public function mediaDelete(MediaEvent $event)
    {
        $this->mediaInfo('php_orchestra_log.media.delete', $event->getMedia()->getName());
    }

    /**
     * @param FolderEvent $event
     */
    public function folderCreate(FolderEvent $event)
    {
        $this->folderInfo('php_orchestra_log.folder.create', $event->getFolder());
    }

    /**
     * @param FolderEvent $event
     */
    public function folderDelete(FolderEvent $event)
    {
        $this->folderInfo('php_orchestra_log.folder.delete', $event->getFolder());
    }

    /**
     * @param FolderEvent $event
     */
    public function folderUpdate(FolderEvent $event)
    {
        $this->folderInfo('php_orchestra_log.folder.update', $event->getFolder());
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            MediaEvents::ADD_IMAGE => 'mediaAddImage',
            MediaEvents::MEDIA_DELETE => 'mediaDelete',
            FolderEvents::FOLDER_CREATE => 'folderCreate',
            FolderEvents::FOLDER_DELETE => 'folderDelete',
            FolderEvents::FOLDER_UPDATE => 'folderUpdate',
        );
    }

    /**
     * @param string $message
     * @param string $mediaName
     */
    protected function mediaInfo($message, $mediaName)
    {
        $this->logger->info($message, array('media_name' => $mediaName));
    }

    /**
     * @param string          $message
     * @param FolderInterface $folder
     */
    protected function folderInfo($message, FolderInterface $folder)
    {
        $this->logger->info($message, array('folder_name' => $folder->getName()));
    }
}
