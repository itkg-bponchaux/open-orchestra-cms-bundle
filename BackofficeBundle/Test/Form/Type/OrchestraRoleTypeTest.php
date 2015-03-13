<?php

namespace OpenOrchestra\BackofficeBundle\Test\Form\Type;

use Phake;
use Doctrine\Common\Collections\ArrayCollection;
use OpenOrchestra\BackofficeBundle\Form\Type\OrchestraRoleChoiceType;

/**
 * Class OrchestraRoleChoiceTypeTest
 */
class OrchestraRoleChoiceTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OrchestraRoleChoiceType
     */
    protected $form;

    protected $roleRepository;
    protected $roles;
    protected $role1;
    protected $role1Name = 'role1Name';
    protected $role2;
    protected $role2Name = 'role2Name';

    public function setUp()
    {
        $this->role1 = Phake::mock('OpenOrchestra\ModelInterface\Model\RoleInterface');
        Phake::when($this->role1)->getName()->thenReturn($this->role1Name);
        $this->role2 = Phake::mock('OpenOrchestra\ModelInterface\Model\RoleInterface');
        Phake::when($this->role2)->getName()->thenReturn($this->role2Name);

        $this->roles = new ArrayCollection();
        $this->roles->add($this->role1);
        $this->roles->add($this->role2);

        $this->roleRepository = Phake::mock('OpenOrchestra\ModelInterface\Repository\RoleRepositoryInterface');
        Phake::when($this->roleRepository)->findAll()->thenReturn($this->roles);

        $this->form = new OrchestraRoleChoiceType($this->roleRepository);
    }

    /**
     * Test instance
     */
    public function testInstance()
    {
        $this->assertInstanceOf('Symfony\Component\Form\AbstractType', $this->form);
    }

    /**
     * Test name
     */
    public function testName()
    {
        $this->assertSame('orchestra_role_choice', $this->form->getName());
    }

    /**
     * Test parent
     */
    public function testParent()
    {
        $this->assertSame('choice', $this->form->getParent());
    }

    /**
     * Test builder
     */
    public function testBuilder()
    {
        $builder = Phake::mock('Symfony\Component\Form\FormBuilder');
        Phake::when($builder)->add(Phake::anyParameters())->thenReturn($builder);
        Phake::when($builder)->addEventSubscriber(Phake::anyParameters())->thenReturn($builder);

        $this->form->buildForm($builder, array());

        Phake::verify($builder, Phake::never())->add(Phake::anyParameters());
        Phake::verify($builder, Phake::never())->addEventSubscriber(Phake::anyParameters());
        Phake::verify($builder, Phake::never())->addEventListener(Phake::anyParameters());
    }

    /**
     * Test resolver
     */
    public function testSetDefaultOptions()
    {
        $resolver = Phake::mock('Symfony\Component\OptionsResolver\OptionsResolverInterface');

        $this->form->setDefaultOptions($resolver);

        Phake::verify($resolver)->setDefaults(array(
            'choices' => array(
                $this->role1Name => $this->role1Name,
                $this->role2Name => $this->role2Name,
            )
        ));
    }
}
