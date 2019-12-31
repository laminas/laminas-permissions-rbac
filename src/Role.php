<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-rbac for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-rbac/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-rbac/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\Permissions\Rbac;

class Role extends AbstractRole
{
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}
