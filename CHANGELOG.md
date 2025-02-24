# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.8] - 24 February 2025
### Fixed
- Rerun LokiCheckoutStepsShippingForwardButton.updateValid() at every update

## [0.1.7] - 24 February 2025
### Fixed
- Hide payment method component by default
- Fix non-existing method NewsletterRepository::getDefaultValue()
- Change Place Order label when virtual quote
- Add missing Dutch translation

## [0.1.6] - 24 February 2025
### Added
- Save in Address Book checkbox
- Add more translation strings

### Fixed
- Prevent null from throwing TypeError / Beautify shipping method label
- Allow saving more variable types
- Call to undefined method getDefaultValue()
- Wrong exception caught
- Prevent saving non-existing payment method still set as default

## [0.1.5] - 24 February 2025
### Fixed
- Prevent exception when selecting shipping method repeatedly
- Allow default value in field to be saved automatically
- Retrieve option model from ViewModel
- Remove empty option for gender
- Remove bgcolor from login card
- Add Dutch translations
- Remove demo field label "Surname"

## [0.1.4] - 23 February 2025
### Fixed
- Intercept exception with addShippingRate

## [0.1.3] - 23 February 2025
### Fixed
- Remove unneeded collectTotals
- Cleanup totals calculation
- Cleanup cart totals rendering
- Failsafe to skip loading shipping methods if country ID is empty
- Make sure default country ID is saved to quote
- Prevent endless loop when submitting to AJAX which then fails
- Simplify shipping method calculation
- Recalculate totals properly via AJAX
- Fix shipping methods test except totals
- Re-enable default shipping method pick
- Dont render address template when HTML is empty anyway
- Add doc link to module overview
- Carrier label not found if carrier no longer active

## [0.1.2] - 22 February 2025
### Fixed
- Update loki_checkout.xml grid CSS for mobile

## [0.1.1] - 22 February 2025
- Small responsive fixes
- Show no billing step title if quote is virtual
- Simplify customer fields block in onestep theme
- Allow customer fields to be shown in either billing or shipping step
- Hide shipping address view and shipping methods for virtual quote
- Hide same-as-billing for virtual quotes
- Empty address was still showing country
- Disable Same as Shipping if quote is virtual
- Allow hiding billing title via XML layout
- Default payment method was not saved to quote
- Lift requirements

## [0.1.0] - 21 February 2025
- Version bump

## [0.0.7] - 21 February 2025
### Added
- New admin page for Module Status
- New admin page for System Checks
- Add reusable RegionProvider

### Fixed
- Set disabled CSS for select as well
- Fix issue with HTML attributes for nested components
- Fix JS numeric validator
- Simplify Agreements handling
- Add DI plugin as workaround for non-working `free_shipping_subtotal`
- Improve streetlines logic with faulty values
- Append streetline values if streetlines config is wrong
- Add "not available" text to unavailable shipping methods
- Add address tag to shipping address preview
- Fix newsletter subscribe for logged in customers
- Rewrite newsletter subscribe logic
- Rename console message from INFO to DEBUG

### Removed
- Remove deprecated `{component_name}` variable for HTML attribute

### Refactor
- Better handling of streetlines
- Rename plugin classes to include `Plugin` suffix
- Refactor LocalMessageRegistry methods
- Refactor `text_select` to avoid unneeded ID suffix

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
