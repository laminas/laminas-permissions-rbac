<?php

/**
 * @see       https://github.com/laminas/laminas-permissions-rbac for the canonical source repository
 * @copyright https://github.com/laminas/laminas-permissions-rbac/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-permissions-rbac/blob/master/LICENSE.md New BSD License
 */
namespace Laminas\Permissions\Rbac\Assertion;

use Laminas\Permissions\Rbac\AssertionInterface;
use Laminas\Permissions\Rbac\Exception\InvalidArgumentException;
use Laminas\Permissions\Rbac\Rbac;

class CallbackAssertion implements AssertionInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback The assertion callback
     */
    public function __construct($callback)
    {
        if (! is_callable($callback)) {
            throw new InvalidArgumentException('Invalid callback provided; not callable');
        }
        $this->callback = $callback;
    }

    /**
     * Assertion method - must return a boolean.
     *
     * Returns the result of the composed callback.
     *
     * @param Rbac $rbac
     * @return bool
     */
    public function assert(Rbac $rbac)
    {
        return (bool) call_user_func($this->callback, $rbac);
    }
}
