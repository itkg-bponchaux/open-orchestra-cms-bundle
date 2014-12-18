<?php

namespace PHPOrchestra\BackofficeBundle\Controller;

use PHPOrchestra\ModelInterface\Model\KeywordInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class KeywordController
 */
class KeywordController extends AbstractAdminController
{
    /**
     * @param Request $request
     * @param int     $keywordId
     *
     * @Config\Route("/keyword/form/{keywordId}", name="php_orchestra_backoffice_keyword_form")
     * @Config\Method({"GET", "POST"})
     *
     * @return Response
     */
    public function formAction(Request $request, $keywordId)
    {
        $keyword = $this->get('php_orchestra_model.repository.keyword')->find($keywordId);

        $form = $this->createForm(
            'keyword',
            $keyword,
            array(
                'action' => $this->generateUrl('php_orchestra_backoffice_keyword_form', array('keywordId' => $keywordId)),
            )
        );

        $form->handleRequest($request);
        $this->handleForm($form, $this->get('translator')->trans('php_orchestra_backoffice.form.keyword.success'), $keyword);

        return $this->renderAdminForm($form);
    }

    /**
     * @param Request $request
     *
     * @Config\Route("/keyword/new", name="php_orchestra_backoffice_keyword_new")
     * @Config\Method({"GET", "POST"})
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $keywordClass = $this->container->getParameter('php_orchestra_model.document.keyword.class');
        /** @var KeywordInterface $keyword */
        $keyword = new $keywordClass();

        $form = $this->createForm(
            'keyword',
            $keyword,
            array(
                'action' => $this->generateUrl('php_orchestra_backoffice_keyword_new'),
            )
        );

        $form->handleRequest($request);
        $this->handleForm($form, $this->get('translator')->trans('php_orchestra_backoffice.form.keyword.creation'), $keyword);

        if ($form->getErrors()->count() > 0) {
            $statusCode = 400;
        } elseif (!is_null($keyword->getId())) {
            $url = $this->generateUrl('php_orchestra_backoffice_keyword_form', array('siteId' => $keyword->getId()));

            return $this->redirect($url);
        } else {
            $statusCode = 200;
        };

        $response = new Response('', $statusCode, array('Content-type' => 'text/html; charset=utf-8'));

        return $this->renderAdminForm($form, array(), $response);
    }
}
