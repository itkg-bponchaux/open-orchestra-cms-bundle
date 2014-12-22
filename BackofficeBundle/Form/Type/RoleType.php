<?php

namespace PHPOrchestra\BackofficeBundle\Form\Type;

use PHPOrchestra\BackofficeBundle\EventSubscriber\AddSubmitButtonSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class RoleType
 */
class RoleType extends AbstractType
{
    protected $roleClass;

    /**
     * @param string $roleClass
     */
    public function __construct($roleClass)
    {
        $this->roleClass = $roleClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'label' => 'php_orchestra_backoffice.form.role.name',
        ));
        $builder->add('fromStatus', 'orchestra_status',array(
            'label' => 'php_orchestra_backoffice.form.role.from_status',
            'required' => false,
        ));
        $builder->add('toStatus', 'orchestra_status',array(
            'label' => 'php_orchestra_backoffice.form.role.to_status',
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
