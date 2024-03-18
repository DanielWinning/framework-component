# Luma | Framework Component Changelog

## [Unreleased]
[patch]
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