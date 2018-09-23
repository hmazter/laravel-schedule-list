# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.1] - 2018-09-23
### Fixed
- Fixed crash when scheduling Closures and Jobs, #23 

## [1.1.0] - 2018-09-04
### Other
- Support Laravel 5.7

## [1.0.0] - 2018-03-09
### Breaking
- Only support PHP 7.1.3 and above
- Only support Laravel 5.5 and above

### Added
- Add `ScheduleList::all()` that returns all scheduled events as an array

## [0.2.1] - 2018-02-07
### Other
- Support Laravel 5.6 and PHPUnit 7

## [0.2.0] - 2017-08-14
### Updated
- Output table include next run occasion for each command
- Description is now parsed from the command class, schedule description overrides the command description

## [0.1.5] - 2017-08-12
### Added
- Support Laravel auto discover

### Other
- Verify with Laravel 5.5 and PHPUnit 6

## [0.1.4] - 2017-01-28
### Updated
- Updated tests
- Use ::class style syntax
- Testing against php 7.1

### Other
- Verify with Laravel 5.4

## [0.1.3] - 2015-12-30
### Updated
- Update string replacement in command output in normal output mode to strip; php, artisan, single and double quote

## [0.1.2] - 2015-12-28
### Updated
- Update dependencies requirements for laravel packages

## [0.1.1] - 2015-09-16
### Added
- Add test for package
- Add travis CI Integration

### Updated
- Update README to clarify package is for Laravel 5

### Fixed
- Fixed typos in README

## [0.1.0] - 2015-08-13
### Added
- Initial release
