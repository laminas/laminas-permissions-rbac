<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-rbac for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-rbac/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-rbac/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Rbac;

use Laminas\Permissions\Rbac;
use LaminasTest\Permissions\Rbac\TestAsset;

/**
 * @category   Laminas
 * @package    Laminas_Permissions
 * @subpackage UnitTests
 * @group      Laminas_Rbac
 */
class RbacTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Laminas\Permissions\Rbac\Rbac
     */
    protected $rbac;

    public function setUp()
    {
        $this->rbac = new Rbac\Rbac();
    }

    public function testIsGrantedAssertion()
    {
        $foo = new Rbac\Role('foo');
        $bar = new Rbac\Role('bar');

        $true  = new TestAsset\SimpleTrueAssertion();
        $false = new TestAsset\SimpleFalseAssertion();

        $roleNoMatch = new TestAsset\RoleMustMatchAssertion($bar);
        $roleMatch   = new TestAsset\RoleMustMatchAssertion($foo);

        $foo->addPermission('can.foo');
        $bar->addPermission('can.bar');

        $this->rbac->addRole($foo);
        $this->rbac->addRole($bar);

        $this->assertEquals(true, $this->rbac->isGranted($foo, 'can.foo', $true));
        $this->assertEquals(false, $this->rbac->isGranted($bar, 'can.bar', $false));

        $this->assertEquals(false, $this->rbac->isGranted($bar, 'can.bar', $roleNoMatch));
        $this->assertEquals(false, $this->rbac->isGranted($bar, 'can.foo', $roleNoMatch));

        $this->assertEquals(true, $this->rbac->isGranted($foo, 'can.foo', $roleMatch));
    }

    public function testIsGrantedSingleRole()
    {
        $foo = new Rbac\Role('foo');
        $foo->addPermission('can.bar');

        $this->rbac->addRole($foo);

        $this->assertEquals(true, $this->rbac->isGranted('foo', 'can.bar'));
        $this->assertEquals(false, $this->rbac->isGranted('foo', 'can.baz'));
    }

    public function testIsGrantedChildRoles()
    {
        $foo = new Rbac\Role('foo');
        $bar = new Rbac\Role('bar');

        $foo->addPermission('can.foo');
        $bar->addPermission('can.bar');

        $this->rbac->addRole($foo);
        $this->rbac->addRole($bar, $foo);

        $this->assertEquals(true, $this->rbac->isGranted('foo', 'can.bar'));
        $this->assertEquals(true, $this->rbac->isGranted('foo', 'can.foo'));
        $this->assertEquals(true, $this->rbac->isGranted('bar', 'can.bar'));

        $this->assertEquals(false, $this->rbac->isGranted('foo', 'can.baz'));
        $this->assertEquals(false, $this->rbac->isGranted('bar', 'can.baz'));
    }

    public function testHasRole()
    {
        $foo = new Rbac\Role('foo');

        $this->rbac->addRole('bar');
        $this->rbac->addRole($foo);

        $this->assertEquals(true, $this->rbac->hasRole($foo));
        $this->assertEquals(true, $this->rbac->hasRole('bar'));
        $this->assertEquals(false, $this->rbac->hasRole('baz'));
    }

    public function testAddRoleFromString()
    {
        $this->rbac->addRole('foo');

        $foo = $this->rbac->getRole('foo');
        $this->assertInstanceOf('Laminas\Permissions\Rbac\Role', $foo);
    }

    public function testAddRoleFromClass()
    {
        $foo = new Rbac\Role('foo');

        $this->rbac->addRole('foo');
        $foo2 = $this->rbac->getRole('foo');

        $this->assertEquals($foo, $foo2);
        $this->assertInstanceOf('Laminas\Permissions\Rbac\Role', $foo2);
    }

    public function testAddRoleWithParentsUsingRbac()
    {
        $foo = new Rbac\Role('foo');
        $bar = new Rbac\Role('bar');

        $this->rbac->addRole($foo);
        $this->rbac->addRole($bar, $foo);

        $this->assertEquals($bar->getParent(), $foo);
        $this->assertEquals(1, count($foo->getChildren()));
    }

    public function testAddRoleWithAutomaticParentsUsingRbac()
    {
        $foo = new Rbac\Role('foo');
        $bar = new Rbac\Role('bar');

        $this->rbac->setCreateMissingRoles(true);
        $this->rbac->addRole($bar, $foo);

        $this->assertEquals($bar->getParent(), $foo);
        $this->assertEquals(1, count($foo->getChildren()));
    }
}
