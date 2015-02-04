<?php

namespace PHPOrchestra\BackofficeBundle\Controller;

use PHPOrchestra\ModelInterface\ContentEvents;
use PHPOrchestra\ModelInterface\Event\ContentEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContentController
 */
class ContentController extends AbstractAdminController
{
    /**
     * @param Request $request
     * @param string  $contentId
     *
     * @Config\Route("/content/form/{contentId}", name="php_orchestra_backoffice_content_form")
     * @Config\Method({"GET", "POST"})
     *
     * @return Response
     */
    public function formAction(Request $request, $contentId)
    {
        $language = $request->get('language');
        $version = $request->get('version');

        $content = $this->get('php_orchestra_model.repository.content')->findOneByContentIdAndLanguageAndVersion($contentId, $language, $version);
        $form = $this->createForm('orchestra_content', $content, array(
            'action' => $this->generateUrl('php_orchestra_backoffice_content_form', array(
                'contentId' => $content->getContentId(),
                'language' => $content->getLanguage(),
                'version' => $content->getVersion(),
            ))
        ));

        $form->handleRequest($request);

        $this->handleForm(
            $form,
            $this->get('translator')->trans('php_orchestra_backoffice.form.content.success'),
            $content
        );

        $this->dispatchEvent(ContentEvents::CONTENT_UPDATE, new ContentEvent($content));

        return $this->renderAdminForm($form);
    }

    /**
     * @param Request $request
     *
     * @Config\Route("/content/new/{contentType}", name="php_orchestra_backoffice_content_new")
     * @Config\Method({"GET", "POST"})
     *
     * @return Response
     */
    public function newAction(Request $request, $contentType)
    {
        $contentClass = $this->container->getParameter('php_orchestra_model.document.content.class');
        $content = new $contentClass();
        $content->setContentType($contentType);
        $content->setLanguage($this->get('php_orchestra.manager.current_site')->getCurrentSiteDefaultLanguage());

        $form = $this->createForm('orchestra_content', $content, array(
            'action' => $this->generateUrl('php_orchestra_backoffice_content_new', array(
                'contentType' => $contentType
            )),
            'method' => 'POST',
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $documentManager = $this->get('doctrine.odm.mongodb.document_manager');
            $documentManager->persist($content);
            $documentManager->flush();

            $this->dispatchEvent(ContentEvents::CONTENT_UPDATE, new ContentEvent($content));

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('php_orchestra_backoffice.form.content.creation')
            );

            return $this->redirect(
                $this->generateUrl('php_orchestra_backoffice_content_form', array(
                    'contentId' => $content->getContentId()
                ))
            );
        }

        return $this->render('PHPOrchestraBackofficeBundle:Editorial:template.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
