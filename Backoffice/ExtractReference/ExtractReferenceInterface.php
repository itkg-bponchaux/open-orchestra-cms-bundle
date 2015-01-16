<?php

namespace PHPOrchestra\Backoffice\ExtractReference;

use PHPOrchestra\ModelInterface\Model\StatusableInterface;

/**
 * Interface ExtractReferenceInterface
 */
interface ExtractReferenceInterface
{
    /**
     * @param StatusableInterface $statusableElement
     *
     * @return bool
     */
    public function support(StatusableInterface $statusableElement);

    /**
     * @param StatusableInterface $statusableElement
     *
     * @return array
     */
    public function extractReference(StatusableInterface $statusableElement);

    /**
     * @return string
     */
    public function getName();
}
