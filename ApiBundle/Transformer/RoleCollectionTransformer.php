<?php

namespace OpenOrchestra\ApiBundle\Transformer;

use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use OpenOrchestra\ApiBundle\Facade\RoleCollectionFacade;

/**
 * Class RoleCollectionTransformer
 */
class RoleCollectionTransformer extends AbstractTransformer
{
    /**
     * @param ArrayCollection $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $facade = new RoleCollectionFacade();

        foreach ($mixed as $role) {
            $facade->addRole($this->getTransformer('role')->transform($role));
        }

        $facade->addLink('_self', $this->generateRoute(
            'open_orchestra_api_role_list',
            array()
        ));

        $facade->addLink('_self_add', $this->generateRoute(
            'open_orchestra_backoffice_role_new',
            array()
        ));

        $facade->addLink('_translate', $this->generateRoute('open_orchestra_api_translate'));

        return $facade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'role_collection';
    }
}
