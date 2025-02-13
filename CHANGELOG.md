# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.6] - 13 February 2025
### Fixed
- Fix address when customer is logged in and has no addresses to select yet
- Prefix options are separated by semicolumn, not column
- Properly switch shipping method after initial saving
- Fix issue in refreshing other targets
- Fix forms without proper name
- Enhance payment method icon logic
- Redirect to cart when AJAX fails with empty quote
- Load default theme if theme is messed up
- Add proper UnknownThemeException

### Refactor
- Refactor Validator to remove dependency with component
- Huge refactor to move context dependencies in the open
- Move CssClass to LokiComponents

## [0.0.5] - 21 January 2025
- Initial public release

## [0.0.4] - 13 January 2025
- Reach MVP level

## [0.0.3] - 19 December 2024
- Move from Magewire to Loki Components

## [0.0.2] - 2 October 2024
- Copy from GitHub repository to GitLab repository
- Rename to FriggaCheckout and then LokiCheckout

## [0.0.1] - 11 July 2024
- Initial proof-of-concept (MagewireCheckout)
