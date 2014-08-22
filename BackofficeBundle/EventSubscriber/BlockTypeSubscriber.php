<?php

namespace PHPOrchestra\BackofficeBundle\EventSubscriber;

use PHPOrchestra\BackofficeBundle\StrategyManager\GenerateFormManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class BlockTypeSubscriber
 */
class BlockTypeSubscriber implements EventSubscriberInterface
{
    protected $generateFormManager;

    /**
     * @param GenerateFormManager $generateFormManager
     */
    public function __construct(GenerateFormManager $generateFormManager)
    {
        $this->generateFormManager = $generateFormManager;
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $this->generateFormManager->buildForm($form, $data);
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $block = $form->getData();
        $blockAttributes = $block->getAttributes();
        $data = $event->getData();

        foreach ($data as $key => $value) {
            if ('component' == $key || 'submit' == $key) {
                continue;
            }
            $blockAttributes[$key] = (is_array(json_decode($value, true)))?json_decode($value, true): $value;
        }

        $block->setAttributes($blockAttributes);
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit'
        );
    }
}