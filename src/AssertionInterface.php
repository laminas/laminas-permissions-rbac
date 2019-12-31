<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-rbac for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-rbac/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-rbac/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Laminas\Permissions\Rbac;

interface AssertionInterface
{
    /**
     * Assertion method - must return a boolean.
     */
    public function assert(Rbac $rbac, RoleInterface $role, string $permission) : bool;
}
