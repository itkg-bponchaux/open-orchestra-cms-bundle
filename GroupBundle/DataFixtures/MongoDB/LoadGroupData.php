<?php

namespace OpenOrchestra\GroupBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OpenOrchestra\Backoffice\LeftPanel\Strategies\AdministrationPanelStrategy;
use OpenOrchestra\Backoffice\LeftPanel\Strategies\ContentTypeForContentPanelStrategy;
use OpenOrchestra\Backoffice\LeftPanel\Strategies\GeneralNodesPanelStrategy;
use OpenOrchestra\Backoffice\LeftPanel\Strategies\TreeNodesPanelStrategy;
use OpenOrchestra\Backoffice\LeftPanel\Strategies\TreeTemplatePanelStrategy;
use OpenOrchestra\WorkflowAdminBundle\LeftPanel\Strategies\FonctionPanelStrategy;
use OpenOrchestra\GroupBundle\Document\Group;
use OpenOrchestra\MediaAdminBundle\LeftPanel\Strategies\TreeFolderPanelStrategy;

/**
 * Class LoadGroupData
 */
class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $group1 = $this->generateGroup('First group', 'site1', 'group1');
        $group1->addRole(AdministrationPanelStrategy::ROLE_ACCESS_REDIRECTION);
        $manager->persist($group1);
        $group2 = $this->generateGroup('Demo group', 'site2', 'group2');
        $group2->addRole(AdministrationPanelStrategy::ROLE_ACCESS_REDIRECTION);
        $manager->persist($group2);
        $group3 = $this->generateGroup('Empty group', 'site3', 'group3');
        $group3->addRole(AdministrationPanelStrategy::ROLE_ACCESS_THEME);
        $manager->persist($group3);

        $groupContentType = $this->generateGroup('Content type group', 'site2', 'groupContentType', AdministrationPanelStrategy::ROLE_ACCESS_CONTENT_TYPE);
        $manager->persist($groupContentType);

        $groupLog = $this->generateGroup('Log group', 'site2', 'groupLog', AdministrationPanelStrategy::ROLE_ACCESS_LOG);
        $manager->persist($groupLog);

        $manager->flush();

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 600;
    }

    /**
     * @param string $name
     * @param string $siteNumber
     * @param string $referenceName
     * @param string $role
     *
     * @return Group
     */
    protected function generateGroup($name, $siteNumber, $referenceName, $role = null)
    {
        $group = new Group();
        $group->setName($name);

        if (is_null($role)) {
            $group->addRole('ROLE_ADMIN');
            $group->addRole('ROLE_FROM_DRAFT_TO_PENDING');
            $group->addRole('ROLE_FROM_PENDING_TO_PUBLISHED');
            $group->addRole('ROLE_FROM_PUBLISHED_TO_DRAFT');
            $group->addRole(FonctionPanelStrategy::ROLE_ACCESS_FONCTION);
            $group->addRole(GeneralNodesPanelStrategy::ROLE_ACCESS_GENERAL_NODE);
            $group->addRole(TreeNodesPanelStrategy::ROLE_ACCESS_TREE_NODE);
            $group->addRole(TreeFolderPanelStrategy::ROLE_ACCESS_TREE_FOLDER);
            $group->addRole(TreeTemplatePanelStrategy::ROLE_ACCESS_TREE_TEMPLATE);
            $group->addRole(ContentTypeForContentPanelStrategy::ROLE_ACCESS_CONTENT_TYPE_FOR_CONTENT);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_CONTENT_TYPE);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_API_CLIENT);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_DELETED);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_KEYWORD);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_STATUS);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_GROUP);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_USER);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_SITE);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_ROLE);
            $group->addRole(AdministrationPanelStrategy::ROLE_ACCESS_LOG);
        } else {
            $group->addRole($role);
        }

        $group->setSite($this->getReference($siteNumber));
        $this->setReference($referenceName, $group);

        return $group;
    }
}
