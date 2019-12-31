<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-rbac for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-rbac/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-rbac/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Permissions\Rbac;

use RecursiveIteratorIterator;

abstract class AbstractRole extends AbstractIterator implements RoleInterface
{
    /**
     * @var null|RoleInterface
     */
    protected $parent;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $permissions = array();

    /**
     * Get the name of the role.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add permission to the role.
     *
     * @param $name
     * @return RoleInterface
     */
    public function addPermission($name)
    {
        $this->permissions[$name] = true;

        return $this;
    }

    /**
     * Checks if a permission exists for this role or any child roles.
     *
     * @param  string $name
     * @return bool
     */
    public function hasPermission($name)
    {
        if (isset($this->permissions[$name])) {
            return true;
        }

        $it = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($it as $leaf) {
            /** @var RoleInterface $leaf */
            if ($leaf->hasPermission($name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a child.
     *
     * @param  RoleInterface|string $child
     * @return Role
     */
    public function addChild($child)
    {
        if (is_string($child)) {
            $child = new Role($child);
        }
        if (!$child instanceof RoleInterface) {
            throw new Exception\InvalidArgumentException(
                'Child must be a string or implement Laminas\Permissions\Rbac\RoleInterface'
            );
        }

        $child->setParent($this);
        $this->children[] = $child;

        return $this;
    }

    /**
     * @param  RoleInterface $parent
     * @return RoleInterface
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return null|RoleInterface
     */
    public function getParent()
    {
        return $this->parent;
    }
}
