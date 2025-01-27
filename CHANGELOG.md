# Luma | Framework Component Changelog

## [1.7.3] - 2024-01-27
### Changed
- Update dependencies

---

## [1.7.2] - 2024-11-19
### Changed
- `Luma::run()` no longer echoes the response - this should now be handled by the calling code.

---

## [1.7.1] - 2024-11-19
### Removed
- Removed unused assets

### Security
- Updated dependencies
- Increase Stan level

---

## [1.7.0] - 2024-10-20
### Added
- N/A

### Changed
- `Luma::run` now returns the response to allow for testing controller responses

### Deprecated
- N/A

### Removed
- Removed `AuthenticatedPanel` pending rework

### Fixed
- N/A

### Security
- Increased unit test coverage
- Increased Stan strictness level

---

## [1.6.2] - 2024-10-17
### Added
- Add `phpstan` for code analysis

### Changed
- Use `require` over `require_once` for Luma config setup

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A

---

## [1.6.1] - 2024-08-29
### Added
- N/A

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Call `loadMiddleware` method inside `Luma::load`

### Security
- N/A

---

## [1.6.0] - 2024-08-29
### Added
- Add new `MiddlewareInterface` and `MiddlewareHandler`

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A

---

## [1.5.2] - 2024-08-24
### Added
- N/A

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Hotfix for `luma:cache:clear` command not matching `.log` files

### Security
- N/A

---

## [1.5.1] - 2024-08-24
### Added
- N/A

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Hotfix for broken `luma:cache:clear` command

### Security
- N/A

---

## [1.5.0] - 2024-08-24
### Added
- N/A

### Changed
- Updated `luma:cache:clear` command to also clear log files

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A

---

## [1.4.0] - 2024-08-23
### Added
- Added new `DatabaseConnectionHelper` class

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A

---

## [1.3.3] - 2024-07-16
### Added
- N/A

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Updated dependencies

---

## [1.3.2] - 2024-05-06
### Added
- Start debugger inside Luma constructor to clean up create project code.

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A

---

## [1.3.1] - 2024-05-06
### Added
- N/A

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Fixed issue with `CacheClearCommand` name not being set.
- Fixed issue with `CacheClearCommand` not deleting files in subdirectories.

### Security
- N/A

---

## [1.3.0] - 2024-05-06
### Added
- Added `copyEnv` step to `Installer` - copies `config/.env.example` to `config/.env`.
- Added `CacheClearCommand` to work alongside planned `bin/luma` file.
- Now caching config parameters.
- Added `symfony/command`

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Updated the view cache directory to `cache/views`.

### Security
- N/A

---

## [1.2.0] - 2024-05-05
### Added
- Added `Luma::getConfigParam` and support for config files

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Updated dependencies

---

## [1.1.0] - 2024-04-29
### Added
- Ensure the currently logged-in user is now refreshed on every request
- Provide route protection attributes for controller methods

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Updated dependencies

---

## [1.0.0] - 2024-04-29
### Added
- Required `lumax/security-component`
- Provide the current user to views when calling the `LumaController::render` method
- Allow passing of custom response headers to `LumaController::respond`
- Implement `LumaController::redirect` method
- Add errors and flash messages, provided to views when calling `render`
- Add database query panel and authenticated panel to debug bar
- Provided static methods to get the current logged-in user, authenticator and user provider on `Luma`

### Changed
- N/A

### Deprecated
- N/A

### Removed
- Removed build pipeline/Jenkins setup

### Fixed
- N/A

### Security
- Updated `lumax/routing-component` and `lumax/dependency-injection-component`

---

## [0.6.2] - 2024-03-18
### Added
- N/A

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Update `lumax/aurora-db` from`1.0.0` to `2.0.0`

--- 

## [0.6.1] - 2024-03-17
### Added
- N/A

### Changed
- No longer send `Content-Length` header if debugging is enabled.

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A

---

## [0.6.0] - 2024-03-17
### Added
- N/A

### Changed
- Made the `$data` argument option in `LumaController::render()`, if no argument is provided the new default `[]` will 
be used.
- `Luma` now requires template and cache directory arguments, allowing for more flexibility.

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Increase test coverage, test render method.

---

## [0.5.0] - 2024-03-17
### Added
- Required `lumax/aurora-db`.
- Establishes database connection if required environment variables are set.
- Sets up `Aurora` for database interaction.

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Increased unit test coverage.

---

## [0.4.4] - 2024-03-17
### Added
- N/A

### Changed
- Increased unit test coverage.
- `LumaController::respond()` now accepts a second optional argument - `$responseCode`.

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Fixed bug where response headers and status code were not being properly set.

### Security
- N/A

---

## [0.4.3] - 2024-03-12
### Added
- N/A

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Remove duplicate namespace declaration.

### Security
- N/A

---

## [0.4.2] - 2024-03-12
### Added
- N/A

### Changed
- Update installer to handle changes to `package.json` in the Luma project.

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- Remove duplicate namespace declaration.

### Security
- N/A

---

## [0.4.1] - 2024-03-02
### Added
- N/A

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Update dependencies.

---

## [0.4.0] - 2024-03-02
### Added
- Added `CHANGELOG.md`.
- Added Jenkinsfiles for automated build/test pipeline.

### Changed
- N/A

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- N/A