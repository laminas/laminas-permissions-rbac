<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Rbac;

use Laminas\Permissions\Rbac\Exception;
use Laminas\Permissions\Rbac\Role;
use Laminas\Permissions\Rbac\RoleInterface;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testConstructor(): void
    {
        $foo = new Role('foo');
        $this->assertInstanceOf(RoleInterface::class, $foo);
    }

    public function testGetName(): void
    {
        $foo = new Role('foo');
        $this->assertEquals('foo', $foo->getName());
    }

    public function testAddPermission(): void
    {
        $foo = new Role('foo');
        $foo->addPermission('bar');
        $foo->addPermission('baz');

        $this->assertTrue($foo->hasPermission('bar'));
        $this->assertTrue($foo->hasPermission('baz'));
    }

    public function testInvalidPermission(): void
    {
        $perm = new \stdClass();
        $foo = new Role('foo');
        $this->expectException(\TypeError::class);
        $foo->addPermission($perm);
    }

    public function testAddChild(): void
    {
        $foo = new Role('foo');
        $bar = new Role('bar');
        $baz = new Role('baz');

        $foo->addChild($bar);
        $foo->addChild($baz);

        $this->assertEquals([$bar, $baz], $foo->getChildren());
    }

    public function testAddParent(): void
    {
        $foo = new Role('foo');
        $bar = new Role('bar');
        $baz = new Role('baz');

        $foo->addParent($bar);
        $foo->addParent($baz);
        $this->assertEquals([$bar, $baz], $foo->getParents());
    }

    public function testPermissionHierarchy(): void
    {
        $foo = new Role('foo');
        $foo->addPermission('foo.permission');

        $bar = new Role('bar');
        $bar->addPermission('bar.permission');

        $baz = new Role('baz');
        $baz->addPermission('baz.permission');

        // create hierarchy bar -> foo -> baz
        $foo->addParent($bar);
        $foo->addChild($baz);

        $this->assertTrue($bar->hasPermission('bar.permission'));
        $this->assertTrue($bar->hasPermission('foo.permission'));
        $this->assertTrue($bar->hasPermission('baz.permission'));

        $this->assertFalse($foo->hasPermission('bar.permission'));
        $this->assertTrue($foo->hasPermission('foo.permission'));
        $this->assertTrue($foo->hasPermission('baz.permission'));

        $this->assertFalse($baz->hasPermission('foo.permission'));
        $this->assertFalse($baz->hasPermission('bar.permission'));
        $this->assertTrue($baz->hasPermission('baz.permission'));
    }

    public function testCircleReferenceWithChild(): void
    {
        $foo = new Role('foo');
        $bar = new Role('bar');
        $baz = new Role('baz');
        $baz->addPermission('baz');

        $foo->addChild($bar);
        $bar->addChild($baz);
        $this->expectException(Exception\CircularReferenceException::class);
        $baz->addChild($foo);
    }

    public function testCircleReferenceWithParent(): void
    {
        $foo = new Role('foo');
        $bar = new Role('bar');
        $baz = new Role('baz');
        $baz->addPermission('baz');

        $foo->addParent($bar);
        $bar->addParent($baz);
        $this->expectException(Exception\CircularReferenceException::class);
        $baz->addParent($foo);
    }

    public function testGetPermissions(): void
    {
        $foo = new Role('foo');
        $foo->addPermission('foo.permission');
        $foo->addPermission('foo.2nd-permission');

        $bar = new Role('bar');
        $bar->addPermission('bar.permission');

        $baz = new Role('baz');
        $baz->addPermission('baz.permission');

        // create hierarchy bar -> foo -> baz
        $foo->addParent($bar);
        $foo->addChild($baz);

        $this->assertEquals([
            'bar.permission',
            'foo.permission',
            'foo.2nd-permission',
            'baz.permission'
        ], $bar->getPermissions());

        $this->assertEquals([
            'bar.permission'
        ], $bar->getPermissions(false));

        $this->assertEquals([
            'foo.permission',
            'foo.2nd-permission',
            'baz.permission'
        ], $foo->getPermissions());

        $this->assertEquals([
            'foo.permission',
            'foo.2nd-permission'
        ], $foo->getPermissions(false));

        $this->assertEquals([
            'baz.permission'
        ], $baz->getPermissions());

        $this->assertEquals([
            'baz.permission'
        ], $baz->getPermissions(false));
    }

    public function testAddTwoChildRole(): void
    {
        $foo = new Role('foo');
        $bar = new Role('bar');
        $baz = new Role('baz');

        $foo->addChild($bar);
        $foo->addChild($baz);

        $this->assertEquals([$foo], $bar->getParents());
        $this->assertEquals([$bar, $baz], $foo->getChildren());
    }

    public function testAddSameParent(): void
    {
        $foo = new Role('foo');
        $bar = new Role('bar');

        $foo->addParent($bar);
        $foo->addParent($bar);

        $this->assertEquals([$bar], $foo->getParents());
    }
}
