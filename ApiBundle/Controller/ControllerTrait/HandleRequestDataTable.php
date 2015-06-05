<?php

namespace OpenOrchestra\ApiBundle\Controller\ControllerTrait;

use Doctrine\ODM\MongoDB\DocumentRepository;
use OpenOrchestra\BaseApi\Facade\FacadeInterface;
use OpenOrchestra\BaseApi\Transformer\TransformerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait HandleRequestDataTable
 */
trait HandleRequestDataTable
{
    /**
     * @param Request              $request
     * @param DocumentRepository   $entityRepository
     * @param array                $mappingEntity
     * @param TransformerInterface $transformerManager
     *
     * @return FacadeInterface
     */
    protected function handleRequestDataTable(Request $request, DocumentRepository $entityRepository, $mappingEntity, TransformerInterface $transformerManager)
    {
        $columns = $request->get('columns');
        $search = $request->get('search');
        $search = (null !== $search && isset($search['value'])) ? $search['value'] : null;
        $order = $request->get('order');
        $skip = $request->get('start');
        $skip = (null !== $skip) ? (int)$skip : null;
        $limit = $request->get('length');
        $limit = (null !== $limit) ? (int)$limit : null;

        $collection = $entityRepository->findForPaginateAndSearch($mappingEntity, $columns, $search, $order, $skip, $limit);
        $recordsTotal = $entityRepository->count();
        $recordsFiltered = $entityRepository->countFilterSearch($mappingEntity, $columns, $search);

        return $this->generateFacadeDataTable($transformerManager, $collection, $recordsTotal, $recordsFiltered);
    }

    /**
     * @param TransformerInterface $transformerManager
     * @param array                $collection
     * @param int                  $recordsTotal
     * @param int                  $recordsFiltered
     *
     * @return FacadeInterface
     */
    protected function generateFacadeDataTable(TransformerInterface $transformerManager, $collection, $recordsTotal, $recordsFiltered)
    {
        $facade = $transformerManager->transform($collection);
        $facade->recordsTotal = $recordsTotal;
        $facade->recordsFiltered = $recordsFiltered;

        return $facade;
    }
}
