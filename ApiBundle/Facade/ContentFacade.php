<?php

namespace PHPOrchestra\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class ContentFacade
 */
class ContentFacade extends DeletedFacade
{
    /**
     * @Serializer\Type("string")
     */
    public $contentType;

    /**
     * @Serializer\Type("integer")
     */
    public $version;

    /**
     * @Serializer\Type("integer")
     */
    public $contentTypeVersion;

    /**
     * @Serializer\Type("string")
     */
    public $language;

    /**
     * @Serializer\Type("PHPOrchestra\ApiBundle\Facade\StatusFacade")
     */
    public $status;

    /**
     * @Serializer\Type("string")
     */
    public $statusLabel;

    /**
     * @Serializer\Type("string")
     */
    public $statusId;

    /**
     * @Serializer\Type("array<PHPOrchestra\ApiBundle\Facade\ContentAttributeFacade>")
     */
    protected $attributes = array();

    /**
     * @param FacadeInterface $facade
     */
    public function addAttribute(FacadeInterface $facade)
    {
        $this->attributes[] = $facade;
    }
}
