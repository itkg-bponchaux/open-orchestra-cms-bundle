<?php

namespace OpenOrchestra\ApiBundle\Controller;

use OpenOrchestra\ApiBundle\Controller\ControllerTrait\HandleRequestDataTable;
use OpenOrchestra\BaseApiBundle\Controller\Annotation as Api;
use OpenOrchestra\Pagination\Configuration\PaginateFinderConfiguration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenOrchestra\BaseApiBundle\Controller\BaseController;
use OpenOrchestra\BaseApi\Context\GroupContext;

/**
 * Class NewsletterSubscribersController
 *
 * @Config\Route("newsletter_subscribers")
 */
class NewsletterSubscribersController extends BaseController
{
    use HandleRequestDataTable;

    /**
     * @param Request $request
     *
     * @Config\Route("", name="open_orchestra_api_newsletter_subscribers_list")
     * @Config\Method({"GET"})
     *
     * @Config\Security("has_role('ROLE_ACCESS_NEWSLETTER_SUBSCRIBERS')")
     *
     * @Api\Serialize()
     *
     * @Api\Groups({GroupContext::G_HIDE_ROLES})
     *
     * @return FacadeInterface
     */
    public function listAction(Request $request)
    {
        $repository =  $this->get('open_orchestra_model.repository.newsletter_subscriber');
        $transformer = $this->get('open_orchestra_api.transformer_manager')->get('newsletter_subscriber_collection');

        $configuration = PaginateFinderConfiguration::generateFromRequest($request);
        $configuration->setDescriptionEntity(array(
            'email'      => array('key' => 'email'),
            'first_name' => array('key' => 'firstName'),
            'last_name'  => array('key' => 'lastName'),
            'company'    => array('key' => 'company'),
            'job'        => array('key' => 'job'),
        ));
        $subscribersCollection = $repository->findAllForPaginate($configuration);
        $recordsTotal = $repository->countAll();
        $recordsFiltered = $repository->countAllWithFilters($configuration);

        $facade = $transformer->transform($subscribersCollection);
        $facade->recordsTotal = $recordsTotal;
        $facade->recordsFiltered = $recordsFiltered;

        return $facade;
    }
}
