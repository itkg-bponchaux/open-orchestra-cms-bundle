<?php

namespace PHPOrchestra\ApiBundle\Controller;

use PHPOrchestra\ApiBundle\Exceptions\HttpException\FolderNotDeletableException;
use PHPOrchestra\Media\Event\FolderEvent;
use PHPOrchestra\Media\FolderEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FolderController
 *
 * @Config\Route("folder")
 */
class FolderController extends BaseController
{
    /**
     * @param string $folderId
     *
     * @Config\Route("/{folderId}/delete", name="php_orchestra_api_folder_delete")
     * @Config\Method({"DELETE"})
     *
     * @throws FolderNotDeletableException
     *
     * @return Response
     */
    public function deleteAction($folderId)
    {
        $folder = $this->get('php_orchestra_media.repository.media_folder')->find($folderId);

        if ($folder) {
            $folderManager = $this->get('php_orchestra_backoffice.manager.media_folder');

            if (!$folderManager->isDeletable($folder)) {
                throw new FolderNotDeletableException();
            }
            $folderManager->deleteTree($folder);
            $this->dispatchEvent(FolderEvents::FOLDER_DELETE, new FolderEvent($folder));
            $this->get('doctrine.odm.mongodb.document_manager')->flush();
        }

        return new Response('', 200);
    }
}
