<?php

namespace PHPOrchestra\BackofficeBundle\Manager;

use PHPOrchestra\ModelBundle\Model\ContentTypeInterface;

/**
 * Class ContentTypeManager
 */
class ContentTypeManager
{
    public function duplicate(ContentTypeInterface $contentType)
    {
        $newContentType = clone $contentType;

        foreach ($contentType->getNames() as $name) {
            $newName = clone $name;
            $newContentType->addName($newName);
        }
        foreach ($contentType->getFields() as $field) {
            $newField = clone $field;
            foreach ($field->getLabels() as $label) {
                $newLabel = clone $label;
                $newField->addLabel($newLabel);
            }

            $newContentType->addFieldType($newField);
        }

        return $newContentType;
    }
}
