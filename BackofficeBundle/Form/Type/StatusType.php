<?php

namespace PHPOrchestra\BackofficeBundle\Form\Type;

use PHPOrchestra\BackofficeBundle\EventListener\TranslateValueInitializerListener;
use PHPOrchestra\BackofficeBundle\EventSubscriber\AddSubmitButtonSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PHPOrchestra\ModelBundle\Repository\ContentTypeRepository;
use PHPOrchestra\Backoffice\Manager\TranslationChoiceManager;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class StatusType
 */
class StatusType extends AbstractType
{
    protected $statusClass;
    protected $translateValueInitializer;

    /**
     * @param string                            $statusClass
     * @param TranslateValueInitializerListener $translateValueInitializer
     */
    public function __construct($statusClass, TranslateValueInitializerListener $translateValueInitializer)
    {
        $this->translateValueInitializer = $translateValueInitializer;
        $this->statusClass = $statusClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this->translateValueInitializer, 'preSetData'));

        $builder->add('name', null, array(
            'label' => 'php_orchestra_backoffice.form.status.name'
        ));
        $builder->add('published', null, array(
            'required' => false,
            'label' => 'php_orchestra_backoffice.form.status.published'
        ));
        $builder->add('initial', null, array(
            'required' => false,
            'label' => 'php_orchestra_backoffice.form.status.initial'
        ));
        $builder->add('labels', 'translated_value_collection', array(
            'label' => 'php_orchestra_backoffice.form.status.labels'
        ));
        $builder->add('fromRole', null, array(
            'required' => false,
            'label' => 'php_orchestra_backoffice.form.status.from_role'
        ));
        $builder->add('toRole', null, array(
            'required' => false,
            'label' => 'php_orchestra_backoffice.form.status.to_role'
        ));
        $builder->addEventSubscriber(new AddSubmitButtonSubscriber());
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'status';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->statusClass
        ));
    }

}
