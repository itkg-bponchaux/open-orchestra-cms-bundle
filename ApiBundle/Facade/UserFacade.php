<?php

namespace OpenOrchestra\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class UserFacade
 */
class UserFacade extends AbstractFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $username;

    /**
     * @Serializer\Type("string")
     */
    public $roles;

    /**
     * @Serializer\Type("string")
     */
    public $groups;
}
