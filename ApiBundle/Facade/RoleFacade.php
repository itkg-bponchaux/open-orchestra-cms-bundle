<?php

namespace PHPOrchestra\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class RoleFacade
 */
class RoleFacade extends AbstractFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $name;
}
