<?php

namespace OpenOrchestra\BackofficeBundle\Controller;

use OpenOrchestra\ModelInterface\ContentTypeEvents;
use OpenOrchestra\ModelInterface\Event\ContentTypeEvent;
use OpenOrchestra\ModelInterface\Model\ContentTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;

/**
 * Class ContentTypeController
 */
class ContentTypeController extends AbstractAdminController
{
    /**
     * @param Request $request
     * @param string  $contentTypeId
     *
     * @Config\Route("/content-type/form/{contentTypeId}", name="open_orchestra_backoffice_content_type_form")
     * @Config\Method({"GET", "POST", "PATCH"})
     *
     * @Config\Security("has_role('ROLE_ACCESS_CONTENT_TYPE')")
     *
     * @return Response
     */
    public function formAction(Request $request, $contentTypeId)
    {
        $contentType = $this->get('open_orchestra_model.repository.content_type')->findOneByContentTypeIdInLastVersion($contentTypeId);
        $newContentType = $this->get('open_orchestra_backoffice.manager.content_type')->duplicate($contentType);

        $form = $this->createForm(
            'content_type',
            $newContentType,
            array(
                'action' => $this->generateUrl('open_orchestra_backoffice_content_type_form', array(
                        'contentTypeId' => $contentTypeId,
                    )),
                'method' => $this->getMethod($request),
            )
        );

        $form->handleRequest($request);
        if (!$request->get('no_save')) {
            $this->handleForm($form, $this->get('translator')->trans('open_orchestra_backoffice.form.content_type.success'), $newContentType);
            $this->dispatchEvent(ContentTypeEvents::CONTENT_TYPE_UPDATE, new ContentTypeEvent($newContentType));
        }

        return $this->render('OpenOrchestraBackofficeBundle::form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     *
     * @Config\Route("/content-type/new", name="open_orchestra_backoffice_content_type_new")
     * @Config\Method({"GET", "POST", "PATCH"})
     *
     * @Config\Security("has_role('ROLE_ACCESS_CONTENT_TYPE')")
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $contentTypeClass = $this->container->getParameter('open_orchestra_model.document.content_type.class');
        /** @var ContentTypeInterface $contentType */
        $contentType = $this->get('open_orchestra_backoffice.manager.content_type')->initializeNewContentType($contentTypeClass);

        $form = $this->createForm(
            'content_type',
            $contentType,
            array(
                'action' => $this->generateUrl('open_orchestra_backoffice_content_type_new', array()),
                'method' => $this->getMethod($request),
            )
        );

        $form->handleRequest($request);
        if (!$request->get('no_save')) {
            $handleForm = $this->handleForm($form, $this->get('translator')->trans('open_orchestra_backoffice.form.content_type.creation'), $contentType);

            if ($handleForm && !is_null($contentType->getId())) {
                $this->dispatchEvent(ContentTypeEvents::CONTENT_TYPE_CREATE, new ContentTypeEvent($contentType));

                return $this->redirect($this->generateUrl('open_orchestra_backoffice_content_type_form', array(
                    'contentTypeId' => $contentType->getContentTypeId()
                )));
            }
        }

        return $this->render('OpenOrchestraBackofficeBundle::form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getMethod(Request $request){

        return $request->get('no_save') ? 'PATCH' : 'POST';
    }
}
