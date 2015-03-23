<?php

namespace OpenOrchestra\BackofficeBundle\Test\Security\Authorization\Voter;

use OpenOrchestra\BackofficeBundle\Security\Authorization\Voter\GroupSiteVoter;
use Phake;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Test GroupSiteVoterTest
 */
class GroupSiteVoterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GroupSiteVoter
     */
    protected $voter;

    protected $contextManager;

    /**
     * Set up the test
     */
    public function setUp()
    {
        $this->contextManager = Phake::mock('OpenOrchestra\BaseBundle\Context\CurrentSiteIdInterface');

        $this->voter = new GroupSiteVoter($this->contextManager);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\Security\Core\Authorization\Voter\VoterInterface', $this->voter);
    }

    /**
     * @param string $class
     *
     * @dataProvider provideClassName
     */
    public function testSupportsClass($class)
    {
        $this->assertTrue($this->voter->supportsClass($class));
    }

    /**
     * @return array
     */
    public function provideClassName()
    {
        return array(
            array('StdClass'),
            array('class'),
            array('string'),
            array('Symfony\Component\Security\Core\Authorization\Voter\VoterInterface'),
            array('OpenOrchestra\BackofficeBundle\Document\Group'),
        );
    }

    /**
     * @param string $attribute
     * @param bool   $supports
     *
     * @dataProvider provideAttributeAndSupport
     */
    public function testSupportsAttribute($attribute, $supports)
    {
        $this->assertSame($supports, $this->voter->supportsAttribute($attribute));
    }

    /**
     * @return array
     */
    public function provideAttributeAndSupport()
    {
        return array(
            array('ROLE_PANEL_GENERAL_NODE', true),
            array('ROLE_PANEL_REDIRECTION', true),
            array('ROLE_PANEL_TREE_NODE', true),
            array('ROLE_ADMIN', false),
            array('ROLE_USER', false),
            array('ROLE_FROM_PUBLISHED_TO_DRAFT', false),
        );
    }

    /**
     * @param array  $roles
     * @param string $accessResponse
     *
     * @dataProvider provideRoleAndAccess
     */
    public function testVote($roles, $accessResponse)
    {
        $siteId1 = '1';
        $siteId2 = '2';
        $role1 = 'ROLE_PANEL_1';
        $role2 = 'ROLE_PANEL_2';
        $site1 = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteInterface');
        Phake::when($site1)->getSiteId()->thenReturn($siteId1);
        $site2 = Phake::mock('OpenOrchestra\ModelInterface\Model\SiteInterface');
        Phake::when($site2)->getSiteId()->thenReturn($siteId2);

        $group1 = Phake::mock('OpenOrchestra\BackofficeBundle\Document\Group');
        Phake::when($group1)->getSite()->thenReturn($site1);
        Phake::when($group1)->getRoles()->thenReturn(array($role1));
        $group2 = Phake::mock('OpenOrchestra\BackofficeBundle\Document\Group');
        Phake::when($group2)->getSite()->thenReturn($site2);
        Phake::when($group2)->getRoles()->thenReturn(array($role2));

        $user = Phake::mock('FOS\UserBundle\Model\GroupableInterface');
        Phake::when($user)->getGroups()->thenReturn(array($group1, $group2));

        $token = Phake::mock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        Phake::when($token)->getUser()->thenReturn($user);

        Phake::when($this->contextManager)->getCurrentSiteId()->thenReturn($siteId1);

        $this->assertSame($accessResponse, $this->voter->vote($token, null, $roles));
    }

    /**
     * @return array
     */
    public function provideRoleAndAccess()
    {
        return array(
            array(array('ROLE_PANEL_1'), VoterInterface::ACCESS_GRANTED),
            array(array('ROLE_PANEL_2'), VoterInterface::ACCESS_DENIED),
            array(array('ROLE_PANEL_3'), VoterInterface::ACCESS_DENIED),
            array(array('ROLE_USER'), VoterInterface::ACCESS_ABSTAIN),
            array(array('ROLE_USER', 'ROLE_PANEL_1'), VoterInterface::ACCESS_GRANTED),
            array(array('ROLE_PANEL_1', 'ROLE_USER'), VoterInterface::ACCESS_GRANTED),
            array(array('ROLE_PANEL_2', 'ROLE_USER'), VoterInterface::ACCESS_DENIED),
            array(array('ROLE_USER', 'ROLE_PANEL_2'), VoterInterface::ACCESS_DENIED),
            array(array('ROLE_PANEL_1', 'ROLE_PANEL_2'), VoterInterface::ACCESS_GRANTED),
            array(array('ROLE_PANEL_2', 'ROLE_PANEL_1'), VoterInterface::ACCESS_GRANTED),
        );
    }
}
