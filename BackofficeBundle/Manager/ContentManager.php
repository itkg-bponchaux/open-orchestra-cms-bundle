<?php

namespace PHPOrchestra\BackofficeBundle\Manager;

use PHPOrchestra\Backoffice\Context\ContextManager;
use PHPOrchestra\ModelInterface\Model\ContentInterface;
use PHPOrchestra\ModelInterface\Repository\ContentRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * Class ContentManager
 */
class ContentManager
{
    protected $contentRepository;
    protected $contextManager;
    protected $documentManager;

    /**
     * @param ContentRepositoryInterface $contentRepository
     * @param ContextManager             $contextManager
     */
    public function __construct(ContentRepositoryInterface $contentRepository, ContextManager $contextManager, DocumentManager $documentManager)
    {
        $this->contentRepository = $contentRepository;
        $this->contextManager = $contextManager;
        $this->documentManager = $documentManager;
    }
    /**
     * @param string $contentId
     * @param string $language
     *
     * @return ContentInterface
     */
    public function createNewLanguageContent($contentId, $language = null)
    {
        if ($language === null) {
            $language = $this->contextManager->getCurrentLocale();
        }

        $content = $this->contentRepository->findOneByContentIdAndLanguage($contentId, $language);

        if($content === null){
            $contentSource = $this->contentRepository->findOneByContentId($contentId);
            if($contentSource !== null){
                $content = clone $contentSource;
                $content->setVersion(1);
                $content->setStatus(null);
                $content->setLanguage($language);
            }
        }

        return $content;
    }

    /**
     * Duplicate a content
     *
     * @param ContentInterface $content
     *
     */
    public function duplicateNode(ContentInterface $content)
    {
        $newContent = clone $content;
        $newContent->setVersion($content->getVersion() + 1);
        $newContent->setStatus(null);

        $this->documentManager->persist($newContent);
        $this->documentManager->flush();
    }


}
