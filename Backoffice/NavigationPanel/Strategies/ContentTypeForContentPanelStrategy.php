<?php

namespace OpenOrchestra\Backoffice\NavigationPanel\Strategies;

use OpenOrchestra\ModelInterface\Repository\ContentTypeRepositoryInterface;
use OpenOrchestra\Backoffice\Context\ContextManager;

/**
 * Class ContentTypeForContentPanel
 */
class ContentTypeForContentPanelStrategy extends AbstractNavigationPanelStrategy
{
    const ROLE_ACCESS_CONTENT_TYPE_FOR_CONTENT = 'ROLE_ACCESS_CONTENT_TYPE_FOR_CONTENT';

    /**
     * @var ContentTypeRepositoryInterface
     */
    protected $contentTypeRepository;

    /**
     * @var ContextManager
     */
    protected $contextManager;

    /**
     * @param ContentTypeRepositoryInterface $contentTypeRepository
     */
    public function __construct(ContentTypeRepositoryInterface $contentTypeRepository, ContextManager $contextManager)
    {
        $this->contentTypeRepository = $contentTypeRepository;
        $this->contextManager = $contextManager;
    }

    /**
     * @return string
     */
    public function show()
    {
        $contentTypes = $this->contentTypeRepository->findAllNotDeletedInLastVersion($this->contextManager->getCurrentLocale());

        return $this->render(
            'OpenOrchestraBackofficeBundle:Tree:showContentTypeForContent.html.twig',
            array(
                'contentTypes' => $contentTypes,
            )
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return self::EDITORIAL;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'content_type_for_content';
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return 30;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return self::ROLE_ACCESS_CONTENT_TYPE_FOR_CONTENT;
    }
}