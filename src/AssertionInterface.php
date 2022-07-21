<?php

declare(strict_types=1);

namespace Laminas\Permissions\Rbac;

interface AssertionInterface
{
    /**
     * Assertion method - must return a boolean.
     */
    public function assert(Rbac $rbac, RoleInterface $role, string $permission): bool;
}
