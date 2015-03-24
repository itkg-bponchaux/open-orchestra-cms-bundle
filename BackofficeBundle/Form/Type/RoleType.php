<?php

namespace OpenOrchestra\BackofficeBundle\Form\Type;

use OpenOrchestra\BackofficeBundle\EventListener\TranslateValueInitializerListener;
use OpenOrchestra\BackofficeBundle\EventSubscriber\AddSubmitButtonSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class RoleType
 */
class RoleType extends AbstractType
{
    protected $translateValueInitializer;
    protected $roleClass;

    /**
     * @param TranslateValueInitializerListener $translateValueInitializer
     * @param string                            $roleClass
     */
    public function __construct(TranslateValueInitializerListener $translateValueInitializer, $roleClass)
    {
        $this->translateValueInitializer = $translateValueInitializer;
        $this->roleClass = $roleClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this->translateValueInitializer, 'preSetData'));

        $builder->add('name', null, array(
            'label' => 'open_orchestra_backoffice.form.role.name',
        ));
        $builder->add('descriptions', 'translated_value_collection', array(
            'label' => 'open_orchestra_backoffice.form.role.descriptions'
        ));
        $builder->add('fromStatus', 'orchestra_status', array(
            'embedded' => false,
            'label' => 'open_orchestra_backoffice.form.role.from_status',
            'required' => false,
        ));
        $builder->add('toStatus', 'orchestra_status', array(
            'embedded' => false,
            'label' => 'open_orchestra_backoffice.form.role.to_status',
            'required' => false,
        ));

        $builder->addEventSubscriber(new AddSubmitButtonSubscriber());
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->roleClass,
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'role';
    }

}
