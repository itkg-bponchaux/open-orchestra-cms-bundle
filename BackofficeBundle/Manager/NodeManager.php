<?php

namespace PHPOrchestra\BackofficeBundle\Manager;

use PHPOrchestra\ModelBundle\Model\NodeInterface;
use PHPOrchestra\ModelBundle\Repository\NodeRepository;

/**
 * Class NodeManager
 */
class NodeManager
{
    /**
     * @var NodeRepository $nodeRepository
     */
    protected $nodeRepository;

    /**
     * Constructor
     *
     * @param NodeRepository $nodeRepository
     */
    public function __construct(NodeRepository $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    /**
     * Duplicate a node
     *
     * @param NodeInterface $node
     *
     * @return NodeInterface
     */
    public function duplicateNode(NodeInterface $node)
    {
        $newNode = clone $node;
        $newNode->setVersion($node->getVersion() + 1);
        $newNode->setAlias('');
        $newNode->setStatus(null);
        foreach ($node->getAreas() as $area) {
            $newArea = clone $area;
            $newNode->addArea($newArea);
        }
        foreach ($node->getBlocks() as $block) {
            $newBlock = clone $block;
            $newNode->addBlock($newBlock);
        }

        return $newNode;
    }

    /**
     * @param NodeInterface  $node
     */
    public function deleteTree(NodeInterface $node)
    {
        $node->setDeleted(true);
        $sons = $this->nodeRepository->findByParentId($node->getNodeId());
        foreach ($sons as $son) {
            $this->deleteTree($son);
        }
    }

    /**
     * @param NodeInterface $node
     * @param string        $nodeId
     *
     * @return NodeInterface
     */
    public function hydrateNodeFromNodeId(NodeInterface $node, $nodeId)
    {
        $oldNode = $this->nodeRepository->findOneByNodeIdAndSiteIdAndLastVersion($nodeId);

        if ($oldNode) {
            foreach ($oldNode->getAreas() as $area) {
                $newArea = clone $area;
                $node->addArea($newArea);
            }
            foreach ($oldNode->getBlocks() as $block) {
                $newBlock = clone $block;
                $node->addBlock($newBlock);
            }
        }

        return $node;
    }
}
