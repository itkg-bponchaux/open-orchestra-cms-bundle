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
     * @param ContextManager                 $contextManager
     * @param string                         $parent
     * @param int                            $weight
     */
    public function __construct(ContentTypeRepositoryInterface $contentTypeRepository, ContextManager $contextManager, $parent, $weight)
    {
        $this->contentTypeRepository = $contentTypeRepository;
        $this->contextManager = $contextManager;
        $this->parent = $parent;
        $this->weight = $weight;
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
    public function getName()
    {
        return 'content_type_for_content';
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return self::ROLE_ACCESS_CONTENT_TYPE_FOR_CONTENT;
    }
}
