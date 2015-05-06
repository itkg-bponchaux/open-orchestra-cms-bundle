<?php

namespace OpenOrchestra\ApiBundle\Transformer;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use OpenOrchestra\ApiBundle\Facade\UiModelFacade;

/**
 * Class UiModelTransformer
 */
class UiModelTransformer extends AbstractTransformer
{
    /**
     * @param mixed $mixed
     *
     * @return FacadeInterface|void
     */
    public function transform($mixed)
    {
        $facade = new UiModelFacade();

        if (array_key_exists('label', $mixed)) {
            $facade->label = $mixed['label'];
        }

        if (array_key_exists('class', $mixed)) {
            $facade->class = $mixed['class'];
        }

        if (array_key_exists('id', $mixed)) {
            $facade->id = $mixed['id'];
        }

        if (array_key_exists('html', $mixed)) {
            $facade->html = $mixed['html'];
        }

        return $facade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ui_model';
    }
}
