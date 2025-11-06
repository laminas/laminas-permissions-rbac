<?php

declare(strict_types=1);

namespace Laminas\Permissions\Rbac\Exception;

class CircularReferenceException extends RuntimeException implements ExceptionInterface
{
}
