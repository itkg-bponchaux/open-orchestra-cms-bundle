<?php

namespace OpenOrchestra\UserAdminBundle\Transformer;

use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\AbstractTransformer;
use OpenOrchestra\UserAdminBundle\Facade\UserFacade;
use OpenOrchestra\UserBundle\Document\User;
use OpenOrchestra\UserAdminBundle\UserFacadeEvents;
use OpenOrchestra\UserAdminBundle\Event\UserFacadeEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class UserTransformer
 */
class UserTransformer extends AbstractTransformer
{
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param User $mixed
     *
     * @return FacadeInterface
     */
    public function transform($mixed)
    {
        $facade = new UserFacade();

        $facade->id = $mixed->getId();
        $facade->username = $mixed->getUsername();
        $facade->roles = implode(',', $mixed->getRoles());
        $facade->groups = implode(',', $mixed->getGroupNames());

        $facade->addLink('_self', $this->generateRoute(
            'open_orchestra_api_user_show',
            array('userId' => $mixed->getId())
        ));
        $facade->addLink('_self_delete', $this->generateRoute(
            'open_orchestra_api_user_delete',
            array('userId' => $mixed->getId())
        ));
        $facade->addLink('_self_form', $this->generateRoute(
            'open_orchestra_user_admin_user_form',
            array('userId' => $mixed->getId())
        ));
        $facade->addLink('_self_panel_1_password_change', $this->generateRoute(
            'open_orchestra_user_admin_user_change_password',
            array('userId' => $mixed->getId())));

        $this->eventDispatcher->dispatch(
            UserFacadeEvents::POST_USER_TRANSFORMATION,
            new UserFacadeEvent($facade)
        );

        return $facade;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

}
