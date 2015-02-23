<?php

namespace OpenOrchestra\BackofficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;

/**
 * Class UnderscoreTemplateController
 */
class UnderscoreTemplateController extends Controller
{
    /**
     * @param string     $language
     * @param string     $templateId
     *
     * @Config\Route("/underscore-template/show/{language}/{templateId}", name="open_orchestra_backoffice_underscore_template_show")
     * @Config\Method({"GET"})
     *
     * @return Response
     */
    public function showAction($language, $templateId)
    {
        $path = 'OpenOrchestraBackofficeBundle:BackOffice:Underscore/' . $templateId . '._tpl.twig';
        if (false !== strpos($templateId, ':')) {
            $path = 'OpenOrchestraBackofficeBundle:BackOffice/Underscore/' . $templateId . '._tpl.twig';
        }
        return $this->render(
            $path,
            array('language' => $language)
        );
    }
}
