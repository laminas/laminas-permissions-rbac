<?php

declare(strict_types=1);

namespace Laminas\Permissions\Rbac\Assertion;

use Closure;
use Laminas\Permissions\Rbac\AssertionInterface;
use Laminas\Permissions\Rbac\Rbac;
use Laminas\Permissions\Rbac\RoleInterface;

class CallbackAssertion implements AssertionInterface
{
    /** @var Closure(Rbac, ?RoleInterface, ?string): bool */
    private Closure $callback;

    public function __construct(callable $callback)
    {
        // Cast callable to a closure to enforce type safety.
        $this->callback = function (
            Rbac $rbac,
            ?RoleInterface $role = null,
            ?string $permission = null
        ) use ($callback): bool {
            return $callback($rbac, $role, $permission);
        };
    }

    /**
     * {@inheritdoc}
     */
    public function assert(Rbac $rbac, RoleInterface $role, string $permission): bool
    {
        return ($this->callback)($rbac, $role, $permission);
    }
}
