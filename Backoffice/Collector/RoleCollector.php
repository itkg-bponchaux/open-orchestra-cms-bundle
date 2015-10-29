<?php

namespace OpenOrchestra\Backoffice\Collector;

use OpenOrchestra\ModelInterface\Repository\RoleRepositoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use OpenOrchestra\ModelInterface\Manager\TranslationChoiceManagerInterface;

/**
 * Class RoleCollector
 */
class RoleCollector
{
    protected $roles = array();
    protected $roleRepository;
    protected $translator;
    protected $translationChoiceManager;

    /**
     * @param RoleRepositoryInterface           $roleRepository
     * @param TranslatorInterface               $translator
     * @param TranslationChoiceManagerInterface $translationChoiceManager
     */
    public function __construct(RoleRepositoryInterface $roleRepository, TranslatorInterface $translator, TranslationChoiceManagerInterface $translationChoiceManager)
    {
        $this->roleRepository = $roleRepository;
        $this->translator = $translator;
        $this->translationChoiceManager = $translationChoiceManager;
    }

    /**
     * @param string $role
     */
    public function loadWorkflowRole()
    {
        $workflowRoles = $this->roleRepository->findWorkflowRole();
        foreach($workflowRoles as $workflowRole) {
            $this->addRoleWithTranslation($workflowRole->getName(), $this->translationChoiceManager->choose($workflowRole->getDescriptions()));
        }
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param string $role
     */
    public function addRole($role)
    {
        $this->addRoleWithTranslation($role, $this->translator->trans('open_orchestra_role.' . strtolower($role), array(), 'role'));
    }

    /**
     * @param string $role
     */
    public function addRoleWithTranslation($role, $translation)
    {
        $this->roles[$role] = $translation;
    }

}
