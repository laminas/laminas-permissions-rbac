<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-rbac for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-rbac/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-rbac/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Rbac\TestAsset;

use Laminas\Permissions\Rbac\AbstractRole;
use Laminas\Permissions\Rbac\AssertionInterface;
use Laminas\Permissions\Rbac\Rbac;

/**
 * @category   Laminas
 * @package    Laminas_Permissions
 * @subpackage UnitTests
 * @group      Laminas_Rbac
 */
class RoleMustMatchAssertion implements AssertionInterface
{
    /**
     * @var AbstractRole
     */
    protected $role;

    public function __construct(AbstractRole $role)
    {
        $this->role = $role;
    }

    /**
     * Assertion method - must return a boolean.
     *
     * @param  Rbac    $bac
     * @return bool
     */
    public function assert(Rbac $rbac)
    {
        return $this->role->getName() == 'foo';
    }
}
