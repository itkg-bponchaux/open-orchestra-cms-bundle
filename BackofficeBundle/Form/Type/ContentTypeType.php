<?php

namespace OpenOrchestra\BackofficeBundle\Form\Type;

use OpenOrchestra\BackofficeBundle\EventListener\TranslateValueInitializerListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use OpenOrchestra\BackofficeBundle\EventSubscriber\AddSubmitButtonSubscriber;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class ContentTypeType
 */
class ContentTypeType extends AbstractType
{
    protected $translateValueInitializer;
    protected $contentTypeClass;
    protected $translator;

    /**
     * @param string                            $contentTypeClass
     * @param TranslatorInterface               $translator
     * @param TranslateValueInitializerListener $translateValueInitializer
     */
    public function __construct(
        $contentTypeClass,
        TranslatorInterface $translator,
        TranslateValueInitializerListener $translateValueInitializer
    )
    {
        $this->translateValueInitializer = $translateValueInitializer;
        $this->contentTypeClass = $contentTypeClass;
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this->translateValueInitializer, 'preSetData'));
        $builder
            ->add('contentTypeId', 'text', array(
                'label' => 'open_orchestra_backoffice.form.content_type.content_type_id',
                'attr' => array(
                    'class' => 'generate-id-dest',
                )
            ))
            ->add('names', 'translated_value_collection', array(
                'label' => 'open_orchestra_backoffice.form.content_type.names'
            ));
        $builder->add('status', 'orchestra_status', array(
                'label' => 'open_orchestra_backoffice.form.content_type.status',
            ));
        $builder->add('fields', 'collection', array(
            'type' => 'field_type',
            'allow_add' => true,
            'allow_delete' => true,
            'label' => 'open_orchestra_backoffice.form.content_type.fields',
            'attr' => array(
                'data-prototype-label-add' => $this->translator->trans('open_orchestra_backoffice.form.field_type.add'),
                'data-prototype-label-new' => $this->translator->trans('open_orchestra_backoffice.form.field_type.new'),
                'data-prototype-label-remove' => $this->translator->trans('open_orchestra_backoffice.form.field_type.delete'),
            )
        ));

        $builder->addEventSubscriber(new AddSubmitButtonSubscriber());
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->contentTypeClass,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'content_type';
    }
}
