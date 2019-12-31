<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-rbac for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-rbac/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-rbac/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Permissions\Rbac;

use RecursiveIterator;

interface RoleInterface extends RecursiveIterator
{
    /**
     * Get the name of the role.
     *
     * @return string
     */
    public function getName();

    /**
     * Add permission to the role.
     *
     * @param $name
     * @return RoleInterface
     */
    public function addPermission($name);

    /**
     * Checks if a permission exists for this role or any child roles.
     *
     * @param  string $name
     * @return bool
     */
    public function hasPermission($name);

    /**
     * Add a child.
     *
     * @param  RoleInterface|string $child
     * @return Role
     */
    public function addChild($child);

    /**
     * @param  RoleInterface $parent
     * @return RoleInterface
     */
    public function setParent($parent);

    /**
     * @return null|RoleInterface|array
     */
    public function getParent();
}
