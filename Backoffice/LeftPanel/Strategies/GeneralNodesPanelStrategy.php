<?php

namespace OpenOrchestra\Backoffice\LeftPanel\Strategies;

use OpenOrchestra\ModelInterface\Model\NodeInterface;
use OpenOrchestra\ModelInterface\Repository\NodeRepositoryInterface;

/**
 * Class GeneralNodesPanel
 */
class GeneralNodesPanelStrategy extends AbstractLeftPaneStrategy
{
    /**
     * @var NodeRepositoryInterface
     */
    protected $nodeRepository;

    /**
     * @param NodeRepositoryInterface $nodeRepository
     */
    public function __construct(NodeRepositoryInterface $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    /**
     * @return string
     */
    public function show()
    {
        $nodes = $this->nodeRepository->findLastVersionBySiteId(NodeInterface::TYPE_GENERAL);

        return $this->render(
            'OpenOrchestraBackofficeBundle:Tree:showGeneralTreeNodes.html.twig',
            array(
                'nodes' => $nodes
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
        return 'generale_node';
    }
}
