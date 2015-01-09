<?php

namespace PHPOrchestra\ApiBundle\Controller;

use PHPOrchestra\ApiBundle\Facade\FacadeInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PHPOrchestra\ApiBundle\Controller\Annotation as Api;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContentController
 *
 * @Config\Route("content")
 */
class ContentController extends BaseController
{
    /**
     * @Config\Route("/languages", name="php_orchestra_api_content_languages_show")
     * @Config\Method({"GET"})
     * @Api\Serialize()
     *
     * @return Response
     */
    public function getAllLanguages()
    {
        return array('languages' => array_keys($this->container->getParameter('php_orchestra_backoffice.orchestra_choice.front_language')));
    }

    /**
     * @param string $contentId
     *
     * @Config\Route("/{contentId}", name="php_orchestra_api_content_show")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @return FacadeInterface
     */
    public function showAction($contentId)
    {
        $content = $this->get('php_orchestra_model.repository.content')->find($contentId);

        return $this->get('php_orchestra_api.transformer_manager')->get('content')->transform($content);
    }

    /**
     * @param Request $request
     *
     * @Config\Route("", name="php_orchestra_api_content_list")
     * @Config\Method({"GET"})
     *
     * @Api\Serialize()
     *
     * @return FacadeInterface
     */
    public function listAction(Request $request)
    {
        $criteria = array('deleted' => false);
        $contentType = $request->get('content_type');

        if ($contentType) {
            $criteria['contentType'] = $contentType;
        }

        $contentCollection = $this->get('php_orchestra_model.repository.content')->findBy($criteria);

        return $this->get('php_orchestra_api.transformer_manager')->get('content_collection')->transform($contentCollection, $contentType);
    }

    /**
     * @param string $contentId
     *
     * @Config\Route("/{contentId}/delete", name="php_orchestra_api_content_delete")
     * @Config\Method({"DELETE"})
     *
     * @return Response
     */
    public function deleteAction($contentId)
    {
        $content = $this->get('php_orchestra_model.repository.content')->find($contentId);
        $content->setDeleted(true);

        $this->get('doctrine.odm.mongodb.document_manager')->flush();

        return new Response('', 200);
    }

    /**
     * @param Request $request
     * @param string  $contentId
     *
     * @Config\Route("/{contentId}/duplicate", name="php_orchestra_api_content_duplicate")
     * @Config\Method({"POST"})
     *
     * @return Response
     */
    public function duplicateAction(Request $request, $contentId)
    {
        $language = $request->get('language');
        /** @var ContentInterface $content */
        $content = $this->get('php_orchestra_model.repository.content')
        ->findOneByContentIdAndLanguageAndVersion($contentId, $language);
        $this->get('php_orchestra_backoffice.manager.content')->duplicateContent($content);

        return new Response('', 200);
    }

    /**
     * @param Request $request
     * @param string  $contentId
     *
     * @Config\Route("/{contentId}/list-version", name="php_orchestra_api_content_list_version")
     * @Config\Method({"GET"})
     * @Api\Serialize()
     *
     * @return Response
     */
    public function listVersionAction(Request $request, $contentId)
    {
        $language = $request->get('language');
        $contents = $this->get('php_orchestra_model.repository.content')->findByContentIdAndLanguage($contentId, $language);

        return $this->get('php_orchestra_api.transformer_manager')->get('content_collection')->transform($contents);
    }

    /**
     * @param Request $request
     * @param string $contentId
     *
     * @Config\Route("/update/{contentId}", name="php_orchestra_api_content_update")
     * @Config\Method({"POST"})
     * @Api\Serialize()
     *
     * @return Response
     */
    public function changeStatusAction(Request $request, $contentId)
    {
        return $this->reverseTransform($request, $contentId, 'content');
    }
}
