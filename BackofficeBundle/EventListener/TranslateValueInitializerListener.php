<?php

namespace PHPOrchestra\BackofficeBundle\EventListener;

use Doctrine\Common\Collections\Collection;
use PHPOrchestra\ModelInterface\Model\TranslatedValueContainerInterface;
use PHPOrchestra\ModelInterface\Model\TranslatedValueInterface;
use Symfony\Component\Form\FormEvent;

/**
 * Class TranslateValueInitializerListener
 */
class TranslateValueInitializerListener
{
    protected $defaultLanguages;
    protected $translatedValueClass;

    /**
     * @param array  $defaultLanguages
     * @param string $translatedValueClass
     */
    public function __construct(array $defaultLanguages, $translatedValueClass)
    {
        $this->defaultLanguages = $defaultLanguages;
        $this->translatedValueClass = $translatedValueClass;
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        /** @var TranslatedValueContainerInterface $data */
        $data = $event->getData();

        if ($data) {
            $translatedProperties = $data->getTranslatedProperties();
            foreach ($translatedProperties as $property) {
                $properties = $data->$property();
                $this->generateDefaultValues($properties);
            }
        }
    }

    /**
     * @param Collection $properties
     */
    protected function generateDefaultValues(Collection $properties)
    {
        foreach ($this->defaultLanguages as $defaultLanguage) {
            if (!$properties->exists(function ($key, $element) use ($defaultLanguage) {
                return $defaultLanguage == $element->getLanguage();
            })
            ) {
                $translatedValueClass = $this->translatedValueClass;
                /** @var TranslatedValueInterface $translatedValue */
                $translatedValue = new $translatedValueClass();
                $translatedValue->setLanguage($defaultLanguage);
                $properties->add($translatedValue);
            }
        }
    }
}
