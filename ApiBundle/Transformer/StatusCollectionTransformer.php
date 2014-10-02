<?php

namespace PHPOrchestra\ApiBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use PHPOrchestra\ApiBundle\Facade\FacadeInterface;
use PHPOrchestra\ApiBundle\Facade\StatusCollectionFacade;

/**
 * Class StatusCollectionTransformer
 */
class StatusCollectionTransformer extends AbstractTransformer
{
    /**
     * @param ArrayCollection $mixed
     *
     * @return FacadeInterface|StatusCollectionFacade
     */
    public function transform($mixed)
    {
        $facade = new StatusCollectionFacade();

        foreach ($mixed as $status) {
            $facade->addStatus($this->getTransformer('status')->transform($status));
        }

        $facade->addLink('_self_add', $this->generateRoute(
            'php_orchestra_backoffice_status_new',
            array()
        ));

        return $facade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'status_collection';
    }

}
