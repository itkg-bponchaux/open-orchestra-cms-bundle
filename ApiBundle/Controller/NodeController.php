<?php

namespace PHPOrchestra\ApiBundle\Controller;

use PHPOrchestra\ApiBundle\Facade\FacadeInterface;
use PHPOrchestra\ModelInterface\Event\NodeEvent;
use PHPOrchestra\ModelInterface\NodeEvents;
use PHPOrchestra\ModelInterface\Model\NodeInterface;
use PHPOrchestra\ApiBundle\Controller\Annotation as Api;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NodeController
 *
 * @Config\Route("node")
 */
class NodeController extends BaseController
{
    /**
     * @param Request $request
     * @param string $nodeId
     *
     * @Config\Route("/{nodeId}", name="php_orchestra_api_node_show")
     * @Config\Method({"GET"})
     * @Api\Serialize()
     *
     * @return FacadeInterface
     */
    public function showAction(Request $request, $nodeId)
    {
        $language = $request->get('language');
        $version = $request->get('version');
        $node = $this->get('php_orchestra_model.repository.node')
            ->findOneByNodeIdAndLanguageAndVersionAndSiteId($nodeId, $language, $version);

        if (!$node) {
            $oldNode = $this->get('php_orchestra_model.repository.node')->findOneByNodeIdAndLanguageAndVersionAndSiteId($nodeId);
            $node = $this->get('php_orchestra_backoffice.manager.node')->createNewLanguageNode($oldNode, $language);
            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            $dm->persist($node);

            $this->get('php_orchestra_backoffice.manager.node')->updateBlockReferences($oldNode, $node);

            $dm->flush();
        }

        return $this->get('php_orchestra_api.transformer_manager')->get('node')->transform($node);
    }

    /**
     * @param string $nodeId
     *
     * @Config\Route("/{nodeId}/delete", name="php_orchestra_api_node_delete")
     * @Config\Method({"DELETE"})
     *
     * @return Response
     */
    public function deleteAction($nodeId)
    {
        $nodes = $this->get('php_orchestra_model.repository.node')->findByNodeIdAndSiteId($nodeId);

        $this->get('php_orchestra_backoffice.manager.node')->deleteTree($nodes);
        $this->get('doctrine.odm.mongodb.document_manager')->flush();
        $this->dispatchEvent(NodeEvents::NODE_DELETE, new NodeEvent($nodes));

        return new Response('', 200);
    }

    /**
     * @param Request $request
     * @param string  $nodeId
     *
     * @Config\Route("/{nodeId}/duplicate", name="php_orchestra_api_node_duplicate")
     * @Config\Method({"POST"})
     *
     * @return Response
     */
    public function duplicateAction(Request $request, $nodeId)
    {
        $language = $request->get('language');
        /** @var NodeInterface $node */
        $node = $this->get('php_orchestra_model.repository.node')
            ->findOneByNodeIdAndLanguageAndVersionAndSiteId($nodeId, $language);
        $newNode = $this->get('php_orchestra_backoffice.manager.node')->duplicateNode($node);

        $this->dispatchEvent(NodeEvents::NODE_DUPLICATE, new NodeEvent($newNode));

        $em = $this->get('doctrine.odm.mongodb.document_manager');
        $em->persist($newNode);

        $this->get('php_orchestra_backoffice.manager.node')->updateBlockReferences($node, $newNode);

        $em->flush();

        return new Response('', 200);
    }

    /**
     * @param Request $request
     * @param string  $nodeId
     *
     * @Config\Route("/{nodeId}/list-version", name="php_orchestra_api_node_list_version")
     * @Config\Method({"GET"})
     * @Api\Serialize()
     *
     * @return Response
     */
    public function listVersionAction(Request $request, $nodeId)
    {
        $language = $request->get('language');
        $node = $this->get('php_orchestra_model.repository.node')->findByNodeIdAndLanguageAndSiteId($nodeId, $language);

        return $this->get('php_orchestra_api.transformer_manager')->get('node_collection')->transformVersions($node);
    }

    /**
     * @param Request $request
     * @param string $nodeMongoId
     *
     * @Config\Route("/update/{nodeMongoId}", name="php_orchestra_api_node_update")
     * @Config\Method({"POST"})
     * @Api\Serialize()
     *
     * @return Response
     */
    public function changeStatusAction(Request $request, $nodeMongoId)
    {
        return $this->reverseTransform($request, $nodeMongoId, 'node');
    }

    /**
     * @param Request $request
     * @param string  $nodeId
     *
     * @Config\Route("/update/children/order/{nodeId}", name="php_orchestra_api_node_update_children_order")
     * @Config\Method({"POST"})
     * @Api\Serialize()
     *
     * @return Response
     */
    public function updateChildrenOrderAction(Request $request, $nodeId)
    {
        $facade = $this->get('jms_serializer')->deserialize(
            $request->getContent(),
            'PHPOrchestra\ApiBundle\Facade\NodeCollectionFacade',
            $request->get('_format', 'json')
        );

        $node = $this->get('php_orchestra_model.repository.node')->findOneByNodeId($nodeId);

        $orderedNode = $this->get('php_orchestra_api.transformer_manager')->get('node_collection')->reverseTransformOrder($facade);

        $this->get('php_orchestra_backoffice.manager.node')->orderNodeChildren($orderedNode, $node);

        $this->get('doctrine.odm.mongodb.document_manager')->flush();

        return new Response('', 200);
    }
}
