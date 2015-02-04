<?php

namespace PHPOrchestra\LogBundle\Test\EventSubscriber;

use Phake;
use PHPOrchestra\LogBundle\EventSubscriber\LogMediaSubscriber;
use PHPOrchestra\Media\MediaEvents;

/**
 * Class LogMediaSubscriberTest
 */
class LogMediaSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LogMediaSubscriber
     */
    protected $subscriber;

    protected $logger;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->logger = Phake::mock('Symfony\Bridge\Monolog\Logger');
        $this->subscriber = new LogMediaSubscriber($this->logger);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->subscriber);
    }

    /**
     * @param string $eventName
     *
     * @dataProvider provideSubscribedEvent
     */
    public function testEventSubscribed($eventName)
    {
        $this->assertArrayHasKey($eventName, $this->subscriber->getSubscribedEvents());
    }

    /**
     * @return array
     */
    public function provideSubscribedEvent()
    {
        return array(
            array(MediaEvents::ADD_IMAGE),
            array(MediaEvents::MEDIA_DELETE),
            array(MediaEvents::OVERRIDE_IMAGE),
            array(MediaEvents::RESIZE_IMAGE),
        );
    }
}
