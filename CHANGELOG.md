# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.6.0 - 2018-02-01

### Added

- [zendframework/zend-permissions-rbac#12](https://github.com/zendframework/zend-permissions-rbac/pull/12) adds
  and publishes the documentation to https://docs.laminas.dev/laminas-permissions-rbac/

- [zendframework/zend-permissions-rbac#23](https://github.com/zendframework/zend-permissions-rbac/pull/23) adds
  support for multiple parent roles, fixing an issue with reverse traversal of
  the inheritance tree. To accomplish this, the method `addParent($parent)` was
  added, and the method `getParent()` now can also return an array of roles.

- [zendframework/zend-permissions-rbac#31](https://github.com/zendframework/zend-permissions-rbac/pull/31) adds
  support for PHP 7.2.

### Changed

- Nothing.

### Deprecated

- [zendframework/zend-permissions-rbac#23](https://github.com/zendframework/zend-permissions-rbac/pull/23)
  deprecates the method `setParent()`. Use `addParent()` instead.

### Removed

- [zendframework/zend-permissions-rbac#29](https://github.com/zendframework/zend-permissions-rbac/pull/29) removes
  support for PHP 5.5.

- [zendframework/zend-permissions-rbac#29](https://github.com/zendframework/zend-permissions-rbac/pull/29) removes
  support for HHVM.

### Fixed

- [zendframework/zend-permissions-rbac#21](https://github.com/zendframework/zend-permissions-rbac/pull/21) fixes
  dynamic assertion checking, adding the AND with permission.
