<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Rbac\Assertion;

use Closure;
use Laminas\Permissions\Rbac;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use stdClass;

class CallbackAssertionTest extends TestCase
{
    /**
     * Ensures callback is set in object
     */
    public function testCallbackIsDecoratedAsClosure(): void
    {
        $callback                 = function (): void {
        };
        $assert                   = new Rbac\Assertion\CallbackAssertion($callback);
        $internalCallbackProperty = $this->extractPrivatePropertyValue('callback', $assert);
        $this->assertNotSame(
            $callback,
            $internalCallbackProperty
        );
        $this->assertInstanceOf(Closure::class, $internalCallbackProperty);
    }

    /**
     * Ensures assert method provides callback with rbac as argument
     */
    public function testAssertMethodPassRbacToCallback(): void
    {
        $rbac   = new Rbac\Rbac();
        $assert = new Rbac\Assertion\CallbackAssertion(function ($rbacArg) use ($rbac) {
            Assert::assertSame($rbacArg, $rbac);
            return false;
        });
        $foo    = new Rbac\Role('foo');
        $foo->addPermission('can.foo');
        $rbac->addRole($foo);
        $this->assertFalse($rbac->isGranted($foo, 'can.foo', $assert));
    }

    /**
     * Ensures assert method returns callback's function value
     */
    public function testAssertMethod(): void
    {
        $rbac = new Rbac\Rbac();
        $foo  = new Rbac\Role('foo');
        $bar  = new Rbac\Role('bar');

        /** @var Closure(Rbac\RoleInterface): Closure $assertRoleMatch */
        $assertRoleMatch = function (Rbac\RoleInterface $role): Closure {
            return fn (): bool => $role->getName() === 'foo';
        };

        $roleNoMatch = new Rbac\Assertion\CallbackAssertion($assertRoleMatch($bar));
        $roleMatch   = new Rbac\Assertion\CallbackAssertion($assertRoleMatch($foo));

        $foo->addPermission('can.foo');
        $bar->addPermission('can.bar');

        $rbac->addRole($foo);
        $rbac->addRole($bar);

        $this->assertFalse($rbac->isGranted($bar, 'can.bar', $roleNoMatch));
        $this->assertFalse($rbac->isGranted($bar, 'can.foo', $roleNoMatch));
        $this->assertTrue($rbac->isGranted($foo, 'can.foo', $roleMatch));
    }

    public function testAssertWithCallable(): void
    {
        $rbac = new Rbac\Rbac();
        $foo  = new Rbac\Role('foo');
        $foo->addPermission('can.foo');
        $rbac->addRole($foo);

        $callable = /**
         * @return true
         */
        function ($rbac, $permission, $role): bool {
            return true;
        };
        $this->assertTrue($rbac->isGranted('foo', 'can.foo', $callable));
        $this->assertFalse($rbac->isGranted('foo', 'can.bar', $callable));
    }

    public function testAssertWithInvalidValue(): void
    {
        $rbac = new Rbac\Rbac();
        $foo  = new Rbac\Role('foo');
        $foo->addPermission('can.foo');
        $rbac->addRole($foo);

        $callable = new stdClass();
        $this->expectException(Rbac\Exception\InvalidArgumentException::class);
        $rbac->isGranted('foo', 'can.foo', $callable);
    }

    /** @return mixed */
    private function extractPrivatePropertyValue(string $propertyName, Rbac\Assertion\CallbackAssertion $assert)
    {
        $reflectionProperty = new ReflectionProperty($assert, $propertyName);
        return $reflectionProperty->getValue($assert);
    }
}
