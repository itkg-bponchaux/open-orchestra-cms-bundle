<?php

namespace PHPOrchestra\Backoffice\GenerateForm\Strategies;

use PHPOrchestra\DisplayBundle\DisplayBlock\DisplayBlockInterface;
use PHPOrchestra\ModelBundle\Model\BlockInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class ContentListByKeywordStrategy
 */
class ContentListByKeywordStrategy extends AbstractBlockStrategy
{
    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    public function support(BlockInterface $block)
    {
        return DisplayBlockInterface::CONTENT_LIST_BY_KEYWORD === $block->getComponent();
    }

    /**
     * @param FormInterface  $form
     * @param BlockInterface $block
     */
    public function buildForm(FormInterface $form, BlockInterface $block)
    {
        $attributes = $block->getAttributes();

        $empty = array(
            'contentTag' => '',
            'class' => '',
            'id' => '',
            'url' => '',
            'characterNumber' => 50,
        );

        $attributes = array_merge($empty, $attributes);

        $form->add('contentKeyword', 'orchestra_keyword_choice', array(
            'mapped' => false,
            'data' => $attributes['contentKeyword'],
            'label' => 'php_orchestra_backoffice.form.content_list.content_keyword',
        ));
        $form->add('class', 'textarea', array(
            'mapped' => false,
            'data' => $attributes['class'],
            'required' => false,
        ));
        $form->add('id', 'text', array(
            'mapped' => false,
            'data' => $attributes['id'],
            'required' => false,
        ));
        $form->add('url', 'orchestra_node_choice', array(
            'mapped' => false,
            'data' => $attributes['url'],
            'label' => 'php_orchestra_backoffice.form.content_list.node',
        ));
        $form->add('characterNumber', 'text', array(
            'mapped' => false,
            'data' => $attributes['characterNumber'],
            'label' => 'php_orchestra_backoffice.form.content_list.nb_characters',
            'required' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'content_list_by_keyword';
    }
}
