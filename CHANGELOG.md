# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.1.18] - 10 April 2025
### Fixed
- Move dep with "yireo/magento2-hyva-theme-auto-registration" to Hyva module
- Add yireo:loki-checkout:modules:dump command

## [0.1.17] - 09 April 2025
### Fixed
- Restructure loki classes for Luma
- Fix width definition of icon in step button

## [0.1.16] - 09 April 2025
### Fixed
- Switch from TimezoneInterface to Timezone because of DI arg scopeType issue
- Allow switching shipping details in sidebar to alternate template
- Finalize Post NL templating and abstract Shipping classes
- Add ext/intl requirement
- If street is 1 line, explode by space

## [0.1.15] - 08 April 2025
### Fixed
- Working version of Post NL form with saved values and tab selection
- Simplify loading state of target components
- Remove redundant address-select template
- Remove unneeded important flag
- Remove select-icon when loading select-field
- Add warning if coupon code failed to apply
- Add generic Location, Timeframe and Address classes, fi with Post NL
- Mobile fixes

## [0.1.14] - 04 April 2025
### Fixed
- Remove overlay when reloading StepForwardButton component

## [0.1.13] - 04 April 2025
### Fixed
- StepBackwardButtonViewModel was missing required method

## [0.1.12] - 04 April 2025
### Fixed
- Add Playwright functional tests
- Move to Mollie-specific DI config to Mollie plugin
- Allow for specific exceptions to redirect back to checkout/cart
- Remove specific step button templates in favor of FieldComponents
- Modify overlay when loading next step
- Add icons to step button
- Refactoring of Integration Tests
- Refactor FinalStep to get rid of legacy OneStep code
- Disable autocomplete for coupon code
- Implement better DOM ID for shipments too
- Add better DOM ID for payment methods
- Remove `positive_number` filter from telephone and fax
- Fix issue with duplicate DOM IDs again (but now with test)
- First draft of pickup locations
- Resolving of shipping method icons
- Enhance CSS of method listings
- Refactor PaymentRedirectUrl plugin to resolver
- Refactor PaymentMethodIcon plugins into resolvers
- Enable `hide_region_when_optional` by default
- Implement LokiCheckoutRepositoryTestCase
- Enable GitLab CI/CD
- Add help text depending upon `enable_vat_id_only_for_company`
- Refactor BillingAddress and ShippingAddress components to generic Address components
- Use separate template for billing address form title
- Do not ever copy address if the SameAddress service says no

## [0.1.11] - 11 March 2025
### Fixed
- Fix issue with grid CSS classes
- Add better exception message when Context::getStepProvider() is missing

## [0.1.10] - 11 March 2025
### Fixed
- Fix order completion for virtual orders
- Move GridLayout from XML layout to config
- Remove default grid class
- Implement option `yireo_loki_checkout/general/default_grid_class`
- Dynamically change title of shipping address form
- Rename `field_name` to `fieldName`
- Fix client-side validation after refactoring to LokiFieldComponents
- Fix age verification in test
- Fix gender validation in test
- Replace PrefixValidator with more generic OptionsValidator
- Add proper date validation of date of birth
- Fix possible error if somebody is spoofing value
- Make sure gender and DOB are required if set so in config
- Accessibility fixes
- Fix styling of country box when there is an error
- Fix location of buttons
- Move required block
- Add module dependencies
- Huge refactoring to move logic into new LokiFieldComponents
- System Check for module dependencies
- Add test for new ViewModel
- Add button label for quote without payment required
- Add default responsiveness to all fields
- Fix name of defaults block
- Re-enable postcode format validator test
- Refactor and adjust styling
- Refactor SystemCheck architecture in admin
- Check for dev modules not being enabled in Production Mode
- Move EmailValidatorTest
- Add missing Dutch translations
- Move email availability in separate validator `email_available`
- Rename `loki-checkout.css_classes` to `loki-components.css_classes`
- Rewrite Alpine.store() APIs
- Add datetime to module overview

## [0.1.9] - 25 February 2025
### Fixed
- Standardize JS event names
- Rename checkout:payment:method-activate to loki-checkout prefix
- Rename loki-checkout-before-redirect to loki-checkout.before-redirect
- Rename loki:dom-update to loki-components:dom-update
- Rename yireo-loki-checkout.component-change to loki-components.component.update
- Add generic LokiComponentsLogger
- Refactor yireoLokiCheckoutLogger to LokiCheckoutLogger object
- StepForwardButton not activated after component updates (use $nextTick)

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
