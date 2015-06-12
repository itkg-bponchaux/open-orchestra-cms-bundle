<?php

namespace OpenOrchestra\ApiBundle\Facade;

use JMS\Serializer\Annotation as Serializer;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;

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
     * @Serializer\Type("OpenOrchestra\ApiBundle\Facade\StatusFacade")
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
     * @Serializer\Type("boolean")
     */
    public $siteLinked;

    /**
     * @Serializer\Type("array<OpenOrchestra\ApiBundle\Facade\ContentAttributeFacade>")
     */
    protected $attributes = array();

    /**
     * @Serializer\Type("array<string,string>")
     */
    protected $linearizeAttributes = array();

    /**
     * @param FacadeInterface $facade
     */
    public function addAttribute(FacadeInterface $facade)
    {
        $this->attributes[] = $facade;
    }

    /**
     * @param FacadeInterface $facade
     */
    public function addLinearizeAttribute(FacadeInterface $facade)
    {
        $this->linearizeAttributes[$facade->name] = $facade->value;
    }
}
