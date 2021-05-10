<?php

declare(strict_types=1);

namespace LaminasTest\Permissions\Rbac\TestAsset;

use Laminas\Permissions\Rbac\AbstractRole;
use Laminas\Permissions\Rbac\AssertionInterface;
use Laminas\Permissions\Rbac\Rbac;
use Laminas\Permissions\Rbac\RoleInterface;

class RoleMustMatchAssertion implements AssertionInterface
{
    public function assert(Rbac $rbac, RoleInterface $role, string $permission) : bool
    {
        return $role->getName() === 'foo';
    }
}
