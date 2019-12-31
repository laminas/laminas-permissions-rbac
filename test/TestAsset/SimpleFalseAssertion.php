<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-rbac for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-rbac/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-rbac/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Permissions\Rbac\TestAsset;

use Laminas\Permissions\Rbac\AssertionInterface;
use Laminas\Permissions\Rbac\Rbac;

/**
 * @group      Laminas_Rbac
 */
class SimpleFalseAssertion implements AssertionInterface
{
    /**
     * Assertion method - must return a boolean.
     *
     * @param  Rbac    $rbac
     * @return bool
     */
    public function assert(Rbac $rbac)
    {
        return false;
    }
}
