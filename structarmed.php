<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;

return Architecture::define()
    ->withPresets(Preset::PSR4(), Preset::CODEQUALITY())
    ->layer('Exception', 'src/Exception')
    ->layer('RoleInterface', 'src/RoleInterface.php')
    ->layer('Role', 'src/Role.php')
    ->layer('Rbac', ['src/Rbac.php', 'src/AssertionInterface.php'])
    ->layer('Assertion', 'src/Assertion')
    ->ruleset([
        'Exception'     => [],
        'RoleInterface' => [],
        'Role'          => ['RoleInterface', 'Exception'],
        'Rbac'          => ['+Role'],
        'Assertion'     => ['+Rbac'],
    ]);
